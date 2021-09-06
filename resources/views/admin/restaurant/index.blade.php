@extends('layouts.admin')

@section('titolo')
    <h2>I tuoi ristoranti</h2>
@endsection

@section('content')



    @if (!($restaurants === false))
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Image</th>
                    <th scope="col">Indirizzo</th>
                    <th scope="col">Descrizione</th>
                    <th scope="col">P. IVA</th>
                    <th scope="col">Azioni</th>

                </tr>
            </thead>
            <tbody>


                @foreach ($restaurants as $restaurant)
                    <tr>
                        <th>{{ $restaurant->id }}</th>
                        <td>{{ $restaurant->name }}</td>
                        <td><img width="100" src="{{ asset('storage/' . $restaurant->image) }}" alt=""></td>
                        <td>{{ $restaurant->address }} <br> {{ $restaurant->city }} <br> {{ $restaurant->cap }}</td>
                        <td>{{ $restaurant->description }}</td>
                        <td>{{ $restaurant->piva }}</td>
                        <td>
                            <a class="btn btn-success" href="{{ route('admin.restaurant.show', $restaurant->id) }}">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a class="btn btn-warning" href="{{ route('admin.restaurant.edit', $restaurant->id) }}">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- DELETE BUTTON WITH MODAL --}}
                            <a class="btn btn-danger" data-toggle="modal"
                                data-target="#deleteModalID{{ $restaurant->id }}"><i class="fas fa-trash-alt"></i></a>

                            <!-- Modal -->
                            <div class="modal fade" id="deleteModalID{{ $restaurant->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Eliminazione - Ristorante N.{{ $restaurant->id }}
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container-fluid">
                                                <strong>ID:</strong> {{ $restaurant->id }}
                                                <br>
                                                <strong>NOME RISTORANTE:</strong> {{ $restaurant->name }}
                                                <br>
                                                <br>
                                                Sei sicuro di voler eliminare questo ristorante ed i suoi contenuti?
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Annulla</button>
                                            <form action="{{ route('admin.restaurant.destroy', $restaurant->id) }}"
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Elimina</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- END DELETE BUTTON WITH MODAL --}}
                        </td>
                    </tr>

                @endforeach

            @else
                {{-- Cosa appare se non hai un ristorante --}}
                <a class="btn btn-success mb-3" href="{{ route('admin.restaurant.create') }}">
                    <i class="fas fa-plus"></i>
                    Nuovo Ristorante
                </a>

                {{-- Badge di informazioni --}}
                <div class="alert alert-secondary alert-dismissible fade show mt-1" role="alert">
                    Non hai nessun ristorante collegato a questo account.
                    <br>
                    Che aspetti a crearne uno?
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    @endif





    </tbody>
    </table>
@endsection
