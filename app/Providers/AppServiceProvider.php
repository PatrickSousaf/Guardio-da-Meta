<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use App\Models\Curso;
use Illuminate\Support\Facades\Auth;
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

            // Menu principal - Dashboard
            $event->menu->add([
                'text' => 'Dashboard',
                'url'  => route('dashboard'),
                'icon' => 'fas fa-fw fa-tachometer-alt',
            ]);

            $event->menu->add(['header' => 'Turmas da Escola']);

            $anos = Curso::all()->groupBy('ano')->sortKeys();

            foreach ($anos as $ano => $cursos) {
                $submenu = [];

                foreach ($cursos as $curso) {
                    // Submenus dos períodos
                    $periodosSubmenu = collect(range(1, $curso->periodos))->map(function ($periodo) use ($curso, $ano) {
                        return [
                            'text' => "{$periodo}º Período",
                            'icon' => 'fas fa-fw fa-check',
                            'url'  => route('admin.periodos.show', [
                                'curso' => $curso->id,
                                'ano' => $ano,
                                'periodo' => $periodo
                            ]),
                        ];
                    })->toArray();

                    // Adiciona o menu Comparativo
                    $periodosSubmenu[] = [
                        'text' => 'Metas e Resultados',
                        'icon' => 'fas fa-fw fa-poll-h',
                        'url'  => route('admin.periodos.comparativo', [
                            'curso' => $curso->id,
                            'ano' => $ano,
                            'periodo' => 1 // necessário para a rota
                        ])
                    ];

                    $submenu[] = [
                        'text' => $curso->nome,
                        'url'  => '#',
                        'submenu' => $periodosSubmenu,
                        'icon' => 'fas fa-fw fa-bookmark',
                    ];
                }

                $event->menu->add([
                    'text'    => "{$ano}º Anos",
                    'icon'    => 'fas fa-fw fa-graduation-cap',
                    'submenu' => $submenu,
                ]);
            }



            if (Auth::check() && (Auth::user()->isManagement() || Auth::user()->isDirector())) {
                $event->menu->add(['header' => 'Administração']);

                $event->menu->add([
                    'text' => 'Gerenciamento dos Anos',
                    'url'  => route('admin.cursos.index'),
                    'icon' => 'fas fa-fw fa-cog',
                ]);

                $event->menu->add([
                    'text' => 'Registrar Usuário',
                    'url'  => route('register'),
                    'icon' => 'fas fa-fw fa-user-plus',
                ]);
                
                $event->menu->add([
                'text' => 'Perfil',
                'url'  => route('profile.edit'),
                'icon' => 'fas fa-fw fa-user',
            ]);

            }
        });
    }
}
