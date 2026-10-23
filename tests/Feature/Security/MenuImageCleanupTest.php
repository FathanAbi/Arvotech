<?php

namespace Tests\Feature\Security;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * SEC-013 / ORPH-01 regression tests.
 *
 * Deleting a menu used to remove only the database row, leaving the uploaded
 * photo behind in `public` storage forever. `MenuController::destroy()` now
 * removes the file from disk before deleting the record, and `update()` reuses
 * the same helper so replacing a photo still cleans up the old one.
 */
class MenuImageCleanupTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function menuWithPhoto(string $path = 'menus/foto.jpg'): Menu
    {
        Storage::disk('public')->put($path, 'gambar');

        return Menu::create([
            'nama' => 'Nasi Kotak',
            'deskripsi' => 'Paket nasi kotak',
            'harga' => 25000,
            'foto' => $path,
        ]);
    }

    public function test_deleting_menu_removes_its_image_file(): void
    {
        $menu = $this->menuWithPhoto();

        Storage::disk('public')->assertExists('menus/foto.jpg');

        $this->actingAs($this->admin())
            ->delete('/admin/menus/'.$menu->id)
            ->assertRedirect(route('admin.menus.index'));

        Storage::disk('public')->assertMissing('menus/foto.jpg');
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }

    public function test_deleting_menu_without_image_does_not_error(): void
    {
        $menu = Menu::create([
            'nama' => 'Air Mineral',
            'deskripsi' => null,
            'harga' => 5000,
            'foto' => null,
        ]);

        $this->actingAs($this->admin())
            ->delete('/admin/menus/'.$menu->id)
            ->assertRedirect(route('admin.menus.index'));

        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }

    public function test_deleting_menu_with_missing_file_does_not_error(): void
    {
        $menu = Menu::create([
            'nama' => 'Nasi Kotak',
            'deskripsi' => 'Paket nasi kotak',
            'harga' => 25000,
            'foto' => 'menus/sudah-hilang.jpg',
        ]);

        $this->actingAs($this->admin())
            ->delete('/admin/menus/'.$menu->id)
            ->assertRedirect(route('admin.menus.index'));

        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }

    public function test_deleting_menu_keeps_other_images_intact(): void
    {
        $menu = $this->menuWithPhoto('menus/dihapus.jpg');

        Storage::disk('public')->put('menus/lainnya.jpg', 'gambar');

        $this->actingAs($this->admin())
            ->delete('/admin/menus/'.$menu->id)
            ->assertRedirect(route('admin.menus.index'));

        Storage::disk('public')->assertMissing('menus/dihapus.jpg');
        Storage::disk('public')->assertExists('menus/lainnya.jpg');
    }

    public function test_updating_menu_replaces_the_old_image(): void
    {
        $menu = $this->menuWithPhoto('menus/lama.jpg');

        $this->actingAs($this->admin())
            ->put('/admin/menus/'.$menu->id, [
                'nama' => 'Nasi Kotak Baru',
                'deskripsi' => 'Paket nasi kotak',
                'harga' => 30000,
                'foto' => UploadedFile::fake()->image('baru.jpg'),
            ])
            ->assertRedirect(route('admin.menus.index'));

        $menu->refresh();

        Storage::disk('public')->assertMissing('menus/lama.jpg');
        Storage::disk('public')->assertExists($menu->foto);
        $this->assertNotSame('menus/lama.jpg', $menu->foto);
        $this->assertSame('Nasi Kotak Baru', $menu->nama);
    }
}
