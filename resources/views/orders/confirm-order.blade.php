@extends('layouts.app')

@section('content')
    <div class="container p-3 bg-body-tertiary">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Calcule seu frete</h6>
                        <label class="form-label" for="cep">CEP</label>
                        <form>
                            <input
                                class="form-control"
                                type="text"
                                placeholder="Digite seu CEP (somente números)"
                                id="cep"
                                maxlength="8"
                                required
                                pattern="[0-9]{8}"
                                title="Use apenas 8 dígitos numéricos"
                            />
                            <button type="button" class="btn btn-primary mt-3" onclick="consultZipCode()">
                                Consultar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
