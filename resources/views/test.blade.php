<ul>
                        @forelse ($datos as $dato)
                        <li>{{ $dato }}</li>

                        @empty
                        <p>
                            No hay datos
                        </p>

                        @endforelse

                    </ul>

@if (count($datos) === 1 )

Hay datos
@elseif(count($datos)>1)

TIENES VARIOS DATOS
@else
No tienes ningun dato
@endif