@extends('layouts.admin')

@section('titolo')
    <h3>Modifica {{ $restaurant->name }}</h3>
    <h5>ID.{{ $restaurant->id }}</h5>
@endsection

@section('content')
    <div class="actions_bar">
        <a href="{{ route('admin.restaurant.index') }}" class="btn btn-primary" role="button" aria-pressed="true"><i
                class="fa fa-chevron-left" aria-hidden="true"></i> Indietro</a>
    </div>
    @include('components.error')

    <form class="mt-5" action="{{ route('admin.restaurant.update', $restaurant->id) }}" method="post"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nome Ristorante</label>
            <input type="text" name="name" id="name" class="form-control"
                placeholder="Inserisci il nome del tuo ristorante" aria-describedby="titleHelp" maxlength="255" required
                value="{{ $restaurant->name }}">
            <small id="titleHelp" class="text-muted">Lunghezza massima: 255 caratteri</small>
        </div>

        <div class="form-group">
            <label for="description">Descrizione</label>
            <textarea class="form-control" name="description" id="description" rows="5" maxlength="500"
                required>{{ $restaurant->description }}</textarea>
            <small id="titleHelp" class="text-muted">Lunghezza massima: 500 caratteri</small>
        </div>

        <div class="form-group">
            <label for="img">Immagine di copertina</label>
            <input type="file" class="form-control-file" name="img" id="img"
                placeholder="Carica la copertina per il tuo ristorante" aria-describedby="ImgHelp">
            <small id="ImgHelp" class="form-text text-muted">Max. 1 MB</small>
        </div>

        <div class="form-group">
            <label for="categories">Categoria</label>
            <select class="form-control" name="categories[]" id="categories" multiple required>
                <option disabled> - Seleziona una categoria - </option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ $category->id == old('category_id', $restaurant->category_id) ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group d-flex">
            <div class="address">
                <label for="address">Indirizzo</label>
                <input type="text" name="address" id="address" class="form-control" placeholder="Indirizzo Via / Viale"
                    aria-describedby="titleHelp" maxlength="255" required value="{{ $restaurant->address }}">
            </div>

            <div class="city">
                <label for="city">Città</label>
                <input type="text" name="city" id="city" class="form-control" placeholder="Città"
                    aria-describedby="titleHelp" maxlength="255" required value="{{ $restaurant->city }}">
            </div>

            <div class="cap">
                <label for="cap">CAP</label>
                <input type="text" name="cap" id="cap" class="form-control" placeholder="CAP" aria-describedby="titleHelp"
                    maxlength="5" required value="{{ $restaurant->cap }}">
            </div>
        </div>

        <div class="form-group">
            <label for="piva">P. IVA</label>
            <input type="text" name="piva" id="piva" class="form-control" placeholder="Ex. 0000000-000"
                aria-describedby="titleHelp" maxlength="11" required value="{{ $restaurant->piva }}">
            <small id="titleHelp" class="text-muted">Lunghezza massima: 11 caratteri</small>
        </div>


        <button type="submit" class="btn btn-block btn-primary">Aggiorna</button>

    </form>
@endsection
