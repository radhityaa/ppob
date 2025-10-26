<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketReply;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $tickets = Ticket::with(['user', 'assignedTo'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $tickets = Ticket::where('user_id', $user->id)
                ->with(['user', 'assignedTo'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('ticket.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ['Layanan', 'Produk', 'Informasi', 'Promo', 'Bug', 'Error', 'Saldo', 'Deposit', 'Transaksi'];
        $priorities = ['Low', 'Medium', 'High', 'Urgent'];

        return view('ticket.create', compact('categories', 'priorities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|in:Layanan,Produk,Informasi,Promo,Bug,Error,Saldo,Deposit,Transaksi',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Urgent'
        ]);

        $ticket = Ticket::create([
            'ticket_number' => Ticket::generateTicketNumber(),
            'user_id' => Auth::id(),
            'category' => $request->category,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'Open'
        ]);

        return redirect()->route('ticket.show', $ticket->id)
            ->with('success', 'Tiket berhasil dibuat dengan nomor: ' . $ticket->ticket_number);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $user = Auth::user();

        // Cek apakah user bisa melihat tiket ini
        if (!$user->hasRole('admin') && $ticket->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat tiket ini.');
        }

        $ticket->load(['user', 'assignedTo', 'replies.user', 'attachments.user']);

        // Ambil semua admin untuk ditampilkan di interface
        $admins = User::role('admin')->get();

        return view('ticket.show', compact('ticket', 'admins'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $user = Auth::user();

        // Hanya admin yang bisa edit tiket
        if (!$user->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit tiket ini.');
        }

        $categories = ['Layanan', 'Produk', 'Informasi', 'Promo', 'Bug', 'Error', 'Saldo', 'Deposit', 'Transaksi'];
        $priorities = ['Low', 'Medium', 'High', 'Urgent'];
        $statuses = ['Open', 'In Progress', 'Resolved', 'Closed'];
        $admins = User::role('admin')->get();

        return view('ticket.edit', compact('ticket', 'categories', 'priorities', 'statuses', 'admins'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        // Hanya admin yang bisa update tiket
        if (!$user->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate tiket ini.');
        }

        $request->validate([
            'category' => 'required|in:Layanan,Produk,Informasi,Promo,Bug,Error,Saldo,Deposit,Transaksi',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|in:Open,In Progress,Resolved,Closed',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $data = $request->all();

        // Jika status diubah ke Resolved atau Closed, set resolved_at
        if (in_array($request->status, ['Resolved', 'Closed']) && $ticket->status !== $request->status) {
            $data['resolved_at'] = now();
        }

        $ticket->update($data);

        // Pesan khusus untuk perubahan status
        $message = 'Tiket berhasil diperbarui.';
        if (isset($data['status']) && $ticket->status !== $request->status) {
            $message = "Status tiket berhasil diubah menjadi: {$data['status']}";
        }

        return redirect()->route('ticket.show', $ticket->id)
            ->with('success', $message);
    }

    /**
     * Update status tiket (untuk admin)
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        // Hanya admin yang bisa mengubah status
        if (!$user->hasRole('admin')) {
            abort(403, 'Hanya admin yang dapat mengubah status tiket.');
        }

        $request->validate([
            'status' => 'required|in:Open,In Progress,Resolved,Closed'
        ]);

        $oldStatus = $ticket->status;
        $newStatus = $request->status;

        $data = ['status' => $newStatus];

        // Jika status diubah ke Resolved atau Closed, set resolved_at
        if (in_array($newStatus, ['Resolved', 'Closed']) && $oldStatus !== $newStatus) {
            $data['resolved_at'] = now();
        }

        $ticket->update($data);

        return redirect()->route('ticket.show', $ticket->id)
            ->with('success', "Status tiket berhasil diubah dari {$oldStatus} menjadi {$newStatus}.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $user = Auth::user();

        // Hanya admin yang bisa hapus tiket
        if (!$user->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus tiket ini.');
        }

        $ticket->delete();

        return redirect()->route('ticket.index')
            ->with('success', 'Tiket berhasil dihapus.');
    }

    /**
     * Store a reply to the ticket
     */
    public function storeReply(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        // Cek apakah user bisa membalas tiket ini
        // User bisa membalas tiket sendiri, atau admin bisa membalas semua tiket
        if (!$user->hasRole('admin') && $ticket->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk membalas tiket ini.');
        }

        $request->validate([
            'message' => 'required|string|max:5000',
            'images.*' => 'nullable|file|image|max:5120|mimes:jpeg,jpg,png,gif,webp,bmp'
        ], [
            'images.*.file' => 'File harus berupa file yang valid.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.max' => 'Ukuran gambar maksimal 5MB.',
            'images.*.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG, GIF, WEBP, BMP.'
        ]);

        $isAdmin = $user->hasRole('admin');

        // Buat balasan
        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'is_admin' => $isAdmin
        ]);

        // Handle upload gambar jika ada
        if ($request->hasFile('images')) {
            try {
                $fileUploadService = new FileUploadService();
                $uploadedFiles = [];
                $errors = [];

                foreach ($request->file('images') as $file) {
                    try {
                        // Upload dan validasi file
                        $fileData = $fileUploadService->uploadImage($file);

                        // Simpan ke database
                        $attachment = TicketAttachment::create([
                            'ticket_id' => $ticket->id,
                            'user_id' => $user->id,
                            'original_name' => $fileData['original_name'],
                            'filename' => $fileData['filename'],
                            'file_path' => $fileData['file_path'],
                            'mime_type' => $fileData['mime_type'],
                            'file_size' => $fileData['file_size'],
                            'file_hash' => $fileData['file_hash'],
                            'is_image' => $fileData['is_image']
                        ]);

                        $uploadedFiles[] = $attachment;
                    } catch (\Exception $e) {
                        $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
                    }
                }

                // Update pesan sukses jika ada gambar yang diupload
                if (count($uploadedFiles) > 0) {
                    $message = 'Balasan berhasil dikirim';
                    if (count($uploadedFiles) > 0) {
                        $message .= ' dengan ' . count($uploadedFiles) . ' gambar';
                    }
                    if (count($errors) > 0) {
                        $message .= '. Beberapa gambar gagal: ' . implode(', ', $errors);
                    }
                    $message .= '.';
                } else {
                    $message = 'Balasan berhasil dikirim.';
                }
            } catch (\Exception $e) {
                $message = 'Balasan berhasil dikirim, tetapi gagal mengupload gambar: ' . $e->getMessage();
            }
        } else {
            $message = 'Balasan berhasil dikirim.';
        }

        // Update status tiket jika admin membalas
        if ($isAdmin && $ticket->status === 'Open') {
            $ticket->update(['status' => 'In Progress']);
        }

        return redirect()->route('ticket.show', $ticket->id)
            ->with('success', $message);
    }

    /**
     * Upload gambar untuk tiket
     */
    public function uploadImage(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        // Cek apakah user bisa upload gambar ke tiket ini
        if (!$user->hasRole('admin') && $ticket->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengupload gambar ke tiket ini.');
        }

        $request->validate([
            'images.*' => 'required|file|image|max:5120|mimes:jpeg,jpg,png,gif,webp,bmp'
        ], [
            'images.*.required' => 'Pilih minimal satu gambar.',
            'images.*.file' => 'File harus berupa file yang valid.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.max' => 'Ukuran gambar maksimal 5MB.',
            'images.*.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG, GIF, WEBP, BMP.'
        ]);

        $uploadedFiles = [];
        $errors = [];

        try {
            $fileUploadService = new FileUploadService();

            foreach ($request->file('images') as $file) {
                try {
                    // Upload dan validasi file
                    $fileData = $fileUploadService->uploadImage($file);

                    // Simpan ke database
                    $attachment = TicketAttachment::create([
                        'ticket_id' => $ticket->id,
                        'user_id' => $user->id,
                        'original_name' => $fileData['original_name'],
                        'filename' => $fileData['filename'],
                        'file_path' => $fileData['file_path'],
                        'mime_type' => $fileData['mime_type'],
                        'file_size' => $fileData['file_size'],
                        'file_hash' => $fileData['file_hash'],
                        'is_image' => $fileData['is_image']
                    ]);

                    $uploadedFiles[] = $attachment;
                } catch (\Exception $e) {
                    $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
                }
            }

            if (count($uploadedFiles) > 0) {
                $message = count($uploadedFiles) . ' gambar berhasil diupload.';
                if (count($errors) > 0) {
                    $message .= ' Beberapa file gagal: ' . implode(', ', $errors);
                }
                return redirect()->route('ticket.show', $ticket->id)
                    ->with('success', $message);
            } else {
                return redirect()->route('ticket.show', $ticket->id)
                    ->with('error', 'Gagal mengupload gambar: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            return redirect()->route('ticket.show', $ticket->id)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus gambar dari tiket
     */
    public function deleteImage(Ticket $ticket, TicketAttachment $attachment)
    {
        $user = Auth::user();

        // Cek apakah user bisa menghapus gambar ini
        if (!$user->hasRole('admin') && $attachment->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus gambar ini.');
        }

        try {
            // Hapus file dari storage
            $fileUploadService = new FileUploadService();
            $fileUploadService->deleteFile($attachment->file_path);

            // Hapus record dari database
            $attachment->delete();

            return redirect()->route('ticket.show', $ticket->id)
                ->with('success', 'Gambar berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('ticket.show', $ticket->id)
                ->with('error', 'Gagal menghapus gambar: ' . $e->getMessage());
        }
    }
}
