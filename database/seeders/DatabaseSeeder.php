<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Sucursal;
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
            'password' => bcrypt('12345678'),
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

    }
}
