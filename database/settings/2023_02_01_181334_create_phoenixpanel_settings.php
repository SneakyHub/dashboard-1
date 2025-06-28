<?php

use App\Classes\LegacySettingsMigration;
use Illuminate\Support\Facades\DB;

class CreatePhoenixPanelSettings extends LegacySettingsMigration
{
    public function up(): void
    {
        $table_exists = DB::table('settings_old')->exists();

        $this->migrator->add('phoenixpanel.admin_token', $table_exists ? $this->getOldValue('SETTINGS::SYSTEM:PHOENIXPANEL:TOKEN', '') : env('PHOENIXPANEL_TOKEN', ''));
        $this->migrator->add('phoenixpanel.user_token', $table_exists ? $this->getOldValue('SETTINGS::SYSTEM:PHOENIXPANEL:ADMIN_USER_TOKEN', '') : '');
        $this->migrator->add('phoenixpanel.panel_url', $table_exists ? $this->getOldValue('SETTINGS::SYSTEM:PHOENIXPANEL:URL', '') : env('PHOENIXPANEL_URL', ''));
        $this->migrator->add('phoenixpanel.per_page_limit', $table_exists ? $this->getOldValue('SETTINGS::SYSTEM:PHOENIXPANEL:PER_PAGE_LIMIT', 200) : 200);
    }

    public function down(): void
    {


        DB::table('settings_old')->insert([
            [
                'key' => 'SETTINGS::SYSTEM:PHOENIXPANEL:TOKEN',
                'value' => $this->getNewValue('admin_token', 'phoenixpanel'),
                'type' => 'string',
                'description' => 'The admin token for the PhoenixPanel panel.',
            ],
            [
                'key' => 'SETTINGS::SYSTEM:PHOENIXPANEL:ADMIN_USER_TOKEN',
                'value' => $this->getNewValue('user_token', 'phoenixpanel'),
                'type' => 'string',
                'description' => 'The user token for the PhoenixPanel panel.',
            ],
            [
                'key' => 'SETTINGS::SYSTEM:PHOENIXPANEL:URL',
                'value' => $this->getNewValue('panel_url', 'phoenixpanel'),
                'type' => 'string',
                'description' => 'The URL for the PhoenixPanel panel.',
            ],
            [
                'key' => 'SETTINGS::SYSTEM:PHOENIXPANEL:PER_PAGE_LIMIT',
                'value' => $this->getNewValue('per_page_limit', 'phoenixpanel'),
                'type' => 'integer',
                'description' => 'The number of servers to show per page.',
            ],
        ]);

        try {
            $this->migrator->delete('phoenixpanel.admin_token');
            $this->migrator->delete('phoenixpanel.user_token');
            $this->migrator->delete('phoenixpanel.panel_url');
            $this->migrator->delete('phoenixpanel.per_page_limit');
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
