@extends('layouts.logdata')
@section('title', 'ChangePswrd')
@section('subtitle', 'MaviApostes - Canvi de contrasenyes')

@section('information')
    <div id="regform">
        <h2>Introdueix la nova contrasenya</h4>
        <form method="post" name="form" action="{{route('/changepswrd/update')}}">
            <!--et mante la session-->@csrf
            <div id="registre"><div id="textbox">*Password:</div><div><input type="password" name="password"/></div></div>
            <div id="registre"><div id="textbox">*Repeteix Password:</div><div><input type="password" name="password2"/></div></div>
            <div id="registre"><input type="submit" name="enviar" value="Canvia de Contrasenya"/></div>
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
                <div style="margin-bottom:10px; color:white;"><b>ACTUALITZACIÓ REALITZADA</b></div>
                <div style="color:white;">{{Session::get("msg")}}<div>
            </div>
        @endif
        @if(Session::has("warning"))
            <div id="regform">
                <div style="color:rgb(204, 153, 0);font-size:24px;"><b>PROCEDIMENT INVALID</b></div>
                <div id="error">{{Session::get("warning")}}<div>
            </div>
        @endif
    </center>
@endsection
