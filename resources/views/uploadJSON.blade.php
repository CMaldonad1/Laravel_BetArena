@extends('layouts.apostes')
@section('title', 'Aposta')
@section('subtitle', 'MaviApostes Admin - Nova Jornada')

@section('navegador')
    <div id="logout"><button onclick="window.location.href='{{route('/log/logout')}}'">LogOut</button></div>
    <div id="logout"><button onclick="window.location.href='{{route('log')}}'">Back</button></div>
    <div id="logout"><button onclick="window.location.href='{{route('/resultats')}}'">Intr. Resultats</button></div>
@endsection

@section('information')
    <div id="regform">
        <form action="{{route('/uploadJornada/upload')}}" method="post" enctype="multipart/form-data">
        @csrf
            <span id="info">Selecciona un JSON per a pujar nova jornada</span>
            <br><span id="info">(una jornada per fitxer)</span>
            <br><br><input type="file" accept=".json" name="novaJornada" id="novaJoranda">
            <div><input type="submit" name="carregarJson" value="Upload"></div>
        </form>
        @if ($errors->any())
            <div id="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        @if (Session::has("JSONerrors"))
            <div id="error">
                <p>{{Session::get("JSONerrors")}}</p>
            </div>
        @endif
    </div>

    @if (Session::has("success"))
        <div id="regform">
            <p style="color:white; margin:0px;">{{Session::get("success")}}</p>
            <div id="divbutton">
                    <div id="textbox">Joranda: {{Session::get("data.jornada")}}</div>
                    <div id="textbox">D.Inici: {{Session::get("data.inici")}}</div>
            </div>
            <center>
                <table>
                    <tr><th>Local</th><th>Visitant</th></tr>
                    @foreach(Session::get("data.partits") as $partit)
                        <tr><td>{{$partit['local']}}</td><td>{{$partit['visitant']}}</td></tr>
                    @endforeach
                </table>
            </center>
        </div>

    @endif

@endsection
