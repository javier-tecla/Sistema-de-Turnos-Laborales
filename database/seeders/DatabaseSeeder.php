<?php

namespace Database\Seeders;

use App\Models\Ausencia;
use App\Models\Categoria;
use App\Models\Cronograma;
use App\Models\Empleado;
use App\Models\Sucursal;
use App\Models\Turno;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /*
        $this->call([
            PermissionTableSeeder::class,
            RoleTableSeeder::class,
            UserTableSeeder::class,
        ]);
        \App\Models\User::factory(40)->create()->each(function($user) {
            $user->assignRole('user');
        });
        \App\Models\UserProfile::factory(43)->create();
        */
        $this->call([
            RoleSeeder::class,
        ]);

        $usuarioSuperAdmin = User::factory()->create([
            'username' => 'superadmin',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'Admin@admin.com',
            'password' => bcrypt('123456789'),
            'phone_number' => '+12398190255',
            'user_type' => 'SUPER ADMINISTRADOR',
            'status' => 'active',
        ]);

        $usuarioSuperAdmin->assignRole('SUPER ADMINISTRADOR');

        // Sucursales
        $sucursales = [
            ['nombre' => 'Casa Matriz', 'direccion' => 'Av. Principal 1234, Centro'],
            ['nombre' => 'Sede Norte', 'direccion' => 'Calle Los Olivos 567, Zona Norte'],
            ['nombre' => 'Sede Sur', 'direccion' => 'Av. Circunvalación 890, Zona Sur'],
            ['nombre' => 'Complejo Industrial', 'direccion' => 'Parque Industrial Lote 12, Zona Franca'],
            ['nombre' => 'Centro de Monitoreo', 'direccion' => 'Edificio Torreón Piso 5, Centro Financiero'],
            ['nombre' => 'Base Aeropuerto', 'direccion' => 'Terminal de Carga, Aeropuerto Internacional'],
            ['nombre' => 'Puerto Seco', 'direccion' => 'Av. Portuaria Km 8, Zona Aduanera'],
            ['nombre' => 'Sede Oriente', 'direccion' => 'Calle Comercio 234, Barrio Oriente'],
            ['nombre' => 'Residencial Los Pinos', 'direccion' => 'Av. Los Pinos 456, Zona Residencial'],
            ['nombre' => 'Centro Logístico', 'direccion' => 'Ruta Nacional Km 15, Parque Logístico'],
            ['nombre' => 'Torre Corporativa', 'direccion' => 'Av. Empresarial 789, Distrito Financiero'],
            ['nombre' => 'Base Minera', 'direccion' => 'Campamento Cerro Rico, Zona Minera Sur'],
            ['nombre' => 'Centro Comercial Plaza Real', 'direccion' => 'Av. Shopping 321, Zona Comercial'],
            ['nombre' => 'Hospital San Jorge', 'direccion' => 'Calle Salud 654, Zona Hospitalaria'],
            ['nombre' => 'Universidad Privada', 'direccion' => 'Campus Universitario Km 5, Zona Académica'],
            ['nombre' => 'Terminal de Buses', 'direccion' => 'Av. Transporte 147, Terminal Terrestre'],
            ['nombre' => 'Club de Campo', 'direccion' => 'Ruta Panorámica Km 22, Zona Recreativa'],
            ['nombre' => 'Condominio Altos del Valle', 'direccion' => 'Calle Las Flores 852, Zona Alta'],
            ['nombre' => 'Parque Tecnológico', 'direccion' => 'Boulevard Innovación 963, Hub Tecnológico'],
            ['nombre' => 'Planta de Energía', 'direccion' => 'Km 45 Carretera Interdepartamental, Sector Energético'],
        ];

       foreach ($sucursales as $sucursal) {
        Sucursal::create($sucursal);
       }

        // Categorías
        $categorias = [
            'Seguridad Física',
            'Seguridad Electrónica',
            'Supervisión',
            'Monitoreo CCTV',
            'Escolta VIP',
            'Custodia de Valores',
            'Control de Accesos',
            'Prevención de Pérdidas',
            'Respuesta Armada',
            'Consultoría en Seguridad',
            'Guardia Patrimonial',
            'Seguridad Perimetral',
            'Protección Ejecutiva',
            'Análisis de Riesgos',
            'Seguridad Informática',
            'Brigada de Emergencia',
            'Vigilancia Móvil',
            'Seguridad Canina (K9)',
            'Operador de Central de Alarmas',
            'Seguridad en Eventos',
        ];

        foreach ($categorias as $nombre) {
            Categoria::create(['nombre' => $nombre]);
        }

        $categoriaTurnos = Categoria::all();

        // Turnos
        $turnos = [
            ['categoria_id' => $categoriaTurnos->where('nombre', 'Seguridad Física')->first()->id, 'nombre' => 'Turno Mañana', 'hora_inicio' => '06:00', 'hora_fin' => '14:00', 'color_fondo' => '#3498db', 'color_texto' => '#ffffff'],
            ['categoria_id' => $categoriaTurnos->where('nombre', 'Seguridad Física')->first()->id, 'nombre' => 'Turno Tarde', 'hora_inicio' => '14:00', 'hora_fin' => '22:00', 'color_fondo' => '#e67e22', 'color_texto' => '#ffffff'],
            ['categoria_id' => $categoriaTurnos->where('nombre', 'Seguridad Física')->first()->id, 'nombre' => 'Turno Noche', 'hora_inicio' => '22:00', 'hora_fin' => '06:00', 'color_fondo' => '#2c3e50', 'color_texto' => '#ffffff'],
            ['categoria_id' => $categoriaTurnos->where('nombre', 'Seguridad Física')->first()->id, 'nombre' => 'Monitoreo Diurno', 'hora_inicio' => '07:00', 'hora_fin' => '19:00', 'color_fondo' => '#27ae60', 'color_texto' => '#ffffff'],
            ['categoria_id' => $categoriaTurnos->where('nombre', 'Seguridad Física')->first()->id, 'nombre' => 'Monitoreo Nocturno', 'hora_inicio' => '19:00', 'hora_fin' => '07:00', 'color_fondo' => '#8e44ad', 'color_texto' => '#ffffff'],
            ['categoria_id' => $categoriaTurnos->where('nombre', 'Seguridad Física')->first()->id, 'nombre' => 'Supervisión General', 'hora_inicio' => '08:00', 'hora_fin' => '18:00', 'color_fondo' => '#f39c12', 'color_texto' => '#000000'],
            ['categoria_id' => $categoriaTurnos->where('nombre', 'Seguridad Física')->first()->id, 'nombre' => 'Guardia 12 Horas', 'hora_inicio' => '06:00', 'hora_fin' => '18:00', 'color_fondo' => '#1abc9c', 'color_texto' => '#ffffff'],
            ['categoria_id' => $categoriaTurnos->where('nombre', 'Seguridad Física')->first()->id, 'nombre' => 'Recepción Matutina', 'hora_inicio' => '07:00', 'hora_fin' => '15:00', 'color_fondo' => '#3498db', 'color_texto' => '#ffffff'],
            ['categoria_id' => $categoriaTurnos->where('nombre', 'Seguridad Física')->first()->id, 'nombre' => 'Recepción Vespertina', 'hora_inicio' => '15:00', 'hora_fin' => '23:00', 'color_fondo' => '#e74c3c', 'color_texto' => '#ffffff'],
            ['categoria_id' => $categoriaTurnos->where('nombre', 'Seguridad Física')->first()->id, 'nombre' => 'Ronda Motorizada', 'hora_inicio' => '18:00', 'hora_fin' => '06:00', 'color_fondo' => '#34495e', 'color_texto' => '#ffffff']
        ];

        foreach ($turnos as $turno) {
            Turno::create($turno);
        }

        // Empleados
        Empleado::factory(100)->create();

        $empleados = Empleado::all();

        // Ausencias
        $ausencias = [
            ['empleado_id' => $empleados->random()->id, 'tipo' => 'vacaciones', 'fecha_inicio' => '2026-01-06', 'fecha_fin' => '2026-01-17', 'estado' => 'aprobado'],
            ['empleado_id' => $empleados->random()->id, 'tipo' => 'medica', 'fecha_inicio' => '2026-00-10', 'fecha_fin' => '2026-02-12', 'estado' => 'aprobado'],
            ['empleado_id' => $empleados->random()->id, 'tipo' => 'permiso', 'fecha_inicio' => '2026-03-15', 'fecha_fin' => '2026-03-15', 'estado' => 'aprobado'],
            ['empleado_id' => $empleados->random()->id, 'tipo' => 'medica', 'fecha_inicio' => '2026-04-01', 'fecha_fin' => '2026-04-05', 'estado' => 'rechazado'],
            ['empleado_id' => $empleados->random()->id, 'tipo' => 'vacaciones', 'fecha_inicio' => '2026-05-20', 'fecha_fin' => '2026-05-31', 'estado' => 'pendiente'],
            ['empleado_id' => $empleados->random()->id, 'tipo' => 'permiso', 'fecha_inicio' => '2026-06-08', 'fecha_fin' => '2026-06-08', 'estado' => 'pendiente'],
            ['empleado_id' => $empleados->random()->id, 'tipo' => 'medica', 'fecha_inicio' => '2026-06-22', 'fecha_fin' => '2026-06-24', 'estado' => 'aprobado'],
            ['empleado_id' => $empleados->random()->id, 'tipo' => 'otro', 'fecha_inicio' => '2026-07-01', 'fecha_fin' => '2026-07-03', 'estado' => 'aprobado'],
            ['empleado_id' => $empleados->random()->id, 'tipo' => 'vacaciones', 'fecha_inicio' => '2026-08-10', 'fecha_fin' => '2026-08-21', 'estado' => 'pendiente'],
            ['empleado_id' => $empleados->random()->id, 'tipo' => 'permiso', 'fecha_inicio' => '2026-09-05', 'fecha_fin' => '2026-09-06', 'estado' => 'aprobado']
        ];

        foreach ($ausencias as $ausencia) {
            Ausencia::create($ausencia);
        }

        // Cronogramas
        Cronograma::factory(3000)->create();

    }
}
