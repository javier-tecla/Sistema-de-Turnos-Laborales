<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Turno extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'categoria_id',
        'nombre',
        'hora_inicio',
        'hora_fin',
        'color_fondo',
        'color_texto',
    ];

    /**
     * Conversión automática de tipos al leer de la base de datos o serializar a JSON.
     */
    protected $casts = [
        'categoria_id' => 'integer',
        // 'hora_inicio' => 'datetime:H:i',
        // 'hora_fin' => 'datetime:H:i',
    ];

    /**
     * Atributos virtuales que se incluyen al serializar el modelo.
     */
    protected $appends = ['duracion', 'descripcion_horario'];

    /**
     * Calcula las horas de duración del turno (hora_fin - hora_inicio).
     * Soporta formatos de base de datos con segundos y turnos nocturnos.
     */
    public function getDuracionAttribute()
    {
        if (!$this->hora_inicio || !$this->hora_fin) {
            return 0;
        }

        // Carbon::parse interpreta correctamente "08:00:00" sin romper por los segundos
        $inicio = Carbon::parse($this->getRawOriginal('hora_inicio'));
        $fin = Carbon::parse($this->getRawOriginal('hora_fin'));

        // Si la hora de fin es numéricamente menor, el turno termina al día siguiente
        if ($fin->lessThan($inicio)) {
            $fin->addDay();
        }

        return $inicio->diffInHours($fin);
    }

    /**
     * Horario del turno en formato legible y limpio: "08:00 - 16:00". 
     */
    public function getDescripcionHorarioAttribute()
    {
        if (!$this->hora_inicio || !$this->hora_fin) {
            return '';
        }

        // Formateamos de forma segura para eliminar los segundos de la base de datos
        $inicio = Carbon::parse($this->getRawOriginal('hora_inicio'))->format('H:i');
        $fin = Carbon::parse($this->getRawOriginal('hora_fin'))->format('H:i');

        return "{$inicio} - {$fin}";
    }

    /**
     * Un turno pertenece a una categoría (turnos.categoria_id).
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * Un turno tiene muchos registros en el cronograma (cronogramas.turno_id).
     */
    public function cronogramas(): HasMany
    {
        return $this->hasMany(Cronograma::class, 'turno_id');
    }
}
