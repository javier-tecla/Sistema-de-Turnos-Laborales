<?php

namespace Database\Factories;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpleadoFactory extends Factory
{
    protected $model = Empleado::class;

    private static $documento = 10000000;

    public function definition(): array
    {
        $genero = $this->faker->randomElement(['M', 'F']);
        $nombres = $genero === 'M'
            ? $this->faker->firstNameMale()
            : $this->faker->firstNameFemale();
            $apellidos = $this->faker->lastName() . ' ' . $this->faker->lastName();

            self::$documento++;

            $numeroDoc = (string) self::$documento;

            $usuario = User::create([
                'username' => strtolower($nombres . '.' . explode(' ', $apellidos)[0] . '.' . $numeroDoc),
                'first_name' => $nombres,
                'last_name' => $apellidos,
                'email' => strtolower($nombres . '.' . explode(' ', $apellidos)[0] . $numeroDoc) . '@seguridad.local',
                'password' => bcrypt($numeroDoc),
                'user_type' => 'user',
                'status' => 'active',
            ]);

            $usuario->assignRole('EMPLEADO');

            return [
                'usuario_id' => $usuario->id,
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'tipo_doc' => $this->faker->randomElement(['CI', 'DNI']),
                'numero_doc' => $numeroDoc,
                'telefono' => $this->faker->numerify('7#######'),
                'direccion' => $this->faker->address(),
                'profesion' => $this->faker->randomElement([
                    'Agente de Seguridad',
                    'Supervisor de Turno',
                    'Operador CCTV',
                    'Escolta VIP',
                    'Guardia Patrimonial',
                    'Jefe de Grupo',
                    'Vigilante Nocturno',
                    'Custodio de Valores',
                    'Recepcionista de Seguridad',
                    'Inspector de Ronda',
                ]),
                'fecha_nacimiento' => $this->faker->dateTimeBetween('-55 years', '-20 years'),
                'genero' => $genero,
                'estado' => $this->faker->randomElement(['activo', 'activo', 'activo', 'inactivo']),
            ];

    }
}
