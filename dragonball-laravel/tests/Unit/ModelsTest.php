<?php

namespace Tests\Unit;

use App\Models\Character;
use App\Models\Planet;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class ModelsTest extends TestCase
{

    public function test_relaciones_entre_personaje_y_planeta(): void
    {
        $this->assertInstanceOf(BelongsTo::class, (new Character())->planet());
        $this->assertInstanceOf(HasMany::class, (new Planet())->characters());
    }

    public function test_is_destroyed_es_booleano(): void
    {
        $planet = new Planet(['is_destroyed' => 1]);

        $this->assertTrue($planet->is_destroyed);
    }
}
