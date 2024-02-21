@extends('layouts.auth_app')
@extends('auth.partials.header')
@section('content')
    <div class="login-main-text">
        <div class="title text-center text-dark">
            <h3><b>Sistema de Reseña Policial</b></h3>
            <h3><b>Servicio de Investigación Penal</b></h3>
        </div>  
    </div>
    <div class="card card-primary">
        <div class="card-header">
            <h4 class="text-primary">Preguntas de Seguridad</h4>
        </div>

        <div class="card-body">
            {!! Form::open(array('route' => 'questions.validation', 'method' => 'POST')) !!}
                <div class="form-group has-feedback">
                    @foreach ($question as $q)
                        <label for="question">{{$q->question}}</label>
                        {!! Form::hidden('id_question', $q->id, array('class' => 'form-control', 'required', 'autofocus')) !!}   
                    @endforeach
                        {!! Form::text('question', null, array('class' => 'form-control', 'required', 'placeholder' => 'Ingresa tu Respuesta')) !!}   
                </div>

                <div class="form-group">
                    {!! Form::button('Ingresar', array('class' => 'btn btn-primary btn-lg btn-block', 'type' => 'submit')) !!}   
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
