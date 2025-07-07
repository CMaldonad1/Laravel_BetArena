@extends('layouts.logdata')
@section('title', 'Signup')
@section('subtitle', 'Crea el teu compte a MaviApostes')

@section('information')
    <div id="regform">
        <h2>Registre Socis</h4>
        <form method="post" name="form" action="{{route('/signup/registre')}}">
            <!--et mante la session-->@csrf
            <div id="registre"><div id="textbox">*E-mail:</div><div><input type="text" name="email" value="{{old('email')}}"/></div></div>
            <div id="registre"><div id="textbox">*Nom:</div><div><input type="text" name="nom" value="{{old('nom')}}"/></div></div>
            <div id="registre"><input type="submit" name="enviar" value="Registra't"/></div>
        </form>

        <div style="color:white">Els camps indicats amb asterisc (*)<br> son camps obligatoris.</div>
    </div>

    <center>
        @if ($errors->any())
        <div id="regform">
            <div style="margin-bottom:0px;"><b>ERRORS EN EL REGISTRE:</b></div>
            <div id="error">
                @foreach ($errors->all() as $error)
                    <p>- {{ $error }}</p>
                @endforeach
            </div>
        </div>
        @endif
        @if(Session::has("msg"))
            <div id="regform">
                <div style="margin-bottom:10px; color:white;"><b>REGISTRE REALITZAT CORRECTAMENT</b></div>
                <div style="color:white;">{{Session::get("msg")}}<div>
            </div>
        @endif
    </center>
@endsection
