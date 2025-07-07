@extends('layouts.logdata')
@section('title', 'Recover Password')
@section('subtitle', 'MaviApostes - Recuperació de contrasenyes')

@section('information')
    <div id="regform">
        <h2>Introdueix l'email de registre</h4>
        <form method="post" name="form" action="{{route('/recover/update')}}">
            <!--et mante la session-->@csrf
            <div id="registre"><div id="textbox">E-mail:</div><div><input type="text" name="email" value="{{old('email')}}"/></div></div>
            <div id="registre"><input type="submit" name="enviar" value="Recupear Contrasenya"/></div>
        </form>
    </div>
    <center>
        @if ($errors->any())
        <div id="regform">
            <div style="margin-bottom:0px;"><b>ERROR:</b></div>
            <div id="error">
                @foreach ($errors->all() as $error)
                    <p>- {{ $error }}</p>
                @endforeach
            </div>
        </div>
        @endif
        @if(Session::has("msg"))
            <div id="regform">
                <div style="margin-bottom:10px; color:white;"><b>EMAIL ENVIAT</b></div>
                <div style="color:white;">{{Session::get("msg")}}<div>
            </div>
        @endif
    </center>
@endsection
