<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use App\Models\Curso;
use Illuminate\Support\Str;

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
                'url'  => route('admin.cursos.index'),
                'icon' => 'fas fa-fw fa-cog',
            ]);

            $anos = Curso::all()->groupBy('ano')->sortKeys();

            foreach ($anos as $ano => $cursos) {
                $submenu = [];

                foreach ($cursos as $curso) {
                    $submenu[] = [
                        'text' => $curso->nome,
                        'url'  => '#',
                        'submenu' => collect(range(1, $curso->periodos))->map(function ($periodo) use ($curso, $ano) {
                            return [
                                'text' => "{$periodo}º Período",
                                'url'  => route('admin.periodos.show', [
                                    'curso' => $curso->id, // Usa ID em vez de slug
                                    'ano' => $ano,
                                    'periodo' => $periodo
                                ]),
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

            $event->menu->add(['header' => 'Configurações da Conta']);

            $event->menu->add([
                'text' => 'Perfil',
                'url'  => route('profile.edit'),
                'icon' => 'fas fa-fw fa-user',
            ]);

            $event->menu->add([
                'text' => 'Alterar Senha',
                'url'  => route('profile.edit'),
                'icon' => 'fas fa-fw fa-lock',
            ]);
        });
    }
}

