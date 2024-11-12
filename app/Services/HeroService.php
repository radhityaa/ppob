<?php

namespace App\Services;

class HeroService
{
    public function update($data, $hero)
    {
        // Simpan nama image lama untuk setiap jenis image
        $imageHeroDashboard = $hero->image_hero_dashboard;
        $imageHeroDashboardDark = $hero->image_hero_dashboard_dark;
        $imageHeroElement = $hero->image_hero_element;
        $imageHeroElementDark = $hero->image_hero_element_dark;

        try {
            // Update data teks
            $hero->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'button_text' => $data['button_text'],
                'button_url' => $data['button_url'],
                'small_text' => $data['small_text'],
            ]);

            // Update setiap image jika ada
            $images = [
                'image_hero_dashboard' => $imageHeroDashboard,
                'image_hero_dashboard_dark' => $imageHeroDashboardDark,
                'image_hero_element' => $imageHeroElement,
                'image_hero_element_dark' => $imageHeroElementDark,
            ];

            foreach ($images as $key => $oldImage) {
                if (isset($data[$key]) && $data[$key] instanceof \Illuminate\Http\UploadedFile) {
                    // Hapus image lama jika ada
                    if ($oldImage) {
                        $oldImagePath = public_path('assets/img/front-pages/landing-page/' . $oldImage);
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }

                    // Simpan image baru
                    $newImage = $data[$key];
                    $newImageName = time() . '_' . $key . '.' . $newImage->getClientOriginalExtension();
                    $newImage->move(public_path('assets/img/front-pages/landing-page'), $newImageName);

                    // Update nama file image di database
                    $hero->$key = $newImageName;
                }
            }


            // Simpan perubahan image
            $hero->save();

            return [
                'success' => true,
                'message' => 'Berhasil menyimpan data',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ];
        }
    }
}
