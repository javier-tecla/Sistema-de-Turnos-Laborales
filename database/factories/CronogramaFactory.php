<?php

namespace Database\Factories;

use App\Models\Cronograma;
use App\Models\Empleado;
use App\Models\Sucursal;
use App\Models\Turno;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cronograma>
 */
class CronogramaFactory extends Factory
{
    protected $model = Cronograma::class;

    private static $usados = [];
    
    public function definition(): array
    {
        $empleado = Empleado::inRandomOrder()->first();
        $turno = Turno::inRandomOrder()->first();
        $sucursal = Sucursal::where('id', '<=', 5)->inRandomOrder()->first();
        $fecha = $this->faker->dateTimeBetween('-2 months', '+2 months')->format('Y-m-d');

        $intentos = 0;
        while (isset(self::$usados[$empleado->id . '-' . $fecha]) && $intentos < 50) {
            $empleado = Empleado::inRandomOrder()->first();
            $fecha = $this->faker->dateTimeBetween('-2 months', '+2 months')->format('Y-m-d');
            $intentos++;
        }

        self::$usados[$empleado->id . '-' . $fecha] = true;

        return [
            'empleado_id' => $empleado->id,
            'turno_id' => $turno->id,
            'sucursal_id' => $sucursal->id,
            'fecha' => $fecha,
        ];
    }
}
