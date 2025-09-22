<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use App\Models\Curso;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app['events']->listen(BuildingMenu::class, function (BuildingMenu $event) {

            $event->menu->add(['header' => 'Turmas da Escola']);

            $event->menu->add([
                'text' => 'Gerenciamento dos Anos',
                'url'  => '/admin/cursos',
                'icon' => 'fas fa-fw fa-lock',
            ]);

            $anos = Curso::all()->groupBy('ano')->sortKeys();

            foreach ($anos as $ano => $cursos) {
                $submenu = [];

                foreach ($cursos as $curso) {
                    $submenu[] = [
                        'text' => $curso->nome,
                        'url'  => '#',
                        'submenu' => collect(range(1, $curso->periodos))->map(function ($p) {
                            return [
                                'text' => "{$p}º Período",
                                'url'  => '#',
                            ];
                        })->toArray(),
                    ];
                }

                $event->menu->add([
                    'text'    => "{$ano}º Anos",
                    'icon'    => 'fas fa-fw fa-share',
                    'submenu' => $submenu,
                ]);
            }

            $event->menu->add(['header' => 'account_settings']);

            $event->menu->add([
                'text' => 'profile',
                'url'  => 'admin/settings',
                'icon' => 'fas fa-fw fa-user',
            ]);

            $event->menu->add([
                'text' => 'change_password',
                'url'  => 'admin/settings',
                'icon' => 'fas fa-fw fa-lock',
            ]);
        });
    }
}
