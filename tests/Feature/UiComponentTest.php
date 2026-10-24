<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class UiComponentTest extends TestCase
{
    public function test_ui_card_component_renders_correctly(): void
    {
        $rendered = Blade::render('<x-ui.card title="Judul Kartu" subtitle="Subjudul Kartu">Konten Kartu</x-ui.card>');

        $this->assertStringContainsString('class="card"', $rendered);
        $this->assertStringContainsString('Judul Kartu', $rendered);
        $this->assertStringContainsString('Subjudul Kartu', $rendered);
        $this->assertStringContainsString('Konten Kartu', $rendered);
    }

    public function test_ui_button_component_renders_button_and_link(): void
    {
        $btn = Blade::render('<x-ui.button variant="primary">Simpan</x-ui.button>');
        $this->assertStringContainsString('<button type="button" class="btn btn-primary">', $btn);
        $this->assertStringContainsString('Simpan', $btn);

        $link = Blade::render('<x-ui.button href="/dashboard" variant="secondary" size="sm">Buka</x-ui.button>');
        $this->assertStringContainsString('<a href="/dashboard" class="btn btn-secondary btn-sm">', $link);
        $this->assertStringContainsString('Buka', $link);
    }

    public function test_ui_badge_component_renders_role_and_type(): void
    {
        $roleBadge = Blade::render('<x-ui.badge role="admin">ADMIN</x-ui.badge>');
        $this->assertStringContainsString('class="badge badge-admin"', $roleBadge);
        $this->assertStringContainsString('ADMIN', $roleBadge);

        $statusBadge = Blade::render('<x-ui.badge type="siap">SIAP</x-ui.badge>');
        $this->assertStringContainsString('class="badge badge-siap"', $statusBadge);
        $this->assertStringContainsString('SIAP', $statusBadge);
    }

    public function test_ui_alert_component_renders_and_supports_dismissible(): void
    {
        $alert = Blade::render('<x-ui.alert type="success" :dismissible="true">Berhasil disimpan</x-ui.alert>');
        $this->assertStringContainsString('class="alert alert-success"', $alert);
        $this->assertStringContainsString('Berhasil disimpan', $alert);
        $this->assertStringContainsString('data-dismiss="alert"', $alert);
    }

    public function test_ui_table_component_renders_headers_and_body(): void
    {
        $table = Blade::render('<x-ui.table :headers="[\'Nama\', \'Aksi\']"><tr><td>Budi</td><td>Lihat</td></tr></x-ui.table>');
        $this->assertStringContainsString('class="table-responsive"', $table);
        $this->assertStringContainsString('<th>Nama</th>', $table);
        $this->assertStringContainsString('<th>Aksi</th>', $table);
        $this->assertStringContainsString('<td>Budi</td>', $table);
    }

    public function test_ui_modal_component_renders_hidden_dialog(): void
    {
        $modal = Blade::render('<x-ui.modal id="modalKonfirmasi" title="Konfirmasi Hapus">Yakin?</x-ui.modal>');
        $this->assertStringContainsString('id="modalKonfirmasi"', $modal);
        $this->assertStringContainsString('class="modal-backdrop"', $modal);
        $this->assertStringContainsString('Konfirmasi Hapus', $modal);
        $this->assertStringContainsString('Yakin?', $modal);
    }

    public function test_ui_form_field_renders_label_and_input(): void
    {
        $field = Blade::render('<x-ui.form-field label="Nama Lengkap" name="nama" :required="true"><input type="text" name="nama" id="nama"></x-ui.form-field>');
        $this->assertStringContainsString('for="nama"', $field);
        $this->assertStringContainsString('Nama Lengkap', $field);
        $this->assertStringContainsString('*', $field);
        $this->assertStringContainsString('<input type="text" name="nama" id="nama">', $field);
    }
}
