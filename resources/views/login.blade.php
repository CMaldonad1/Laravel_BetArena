@extends('layouts.apostes')

@section('navegador')
    @if(session()->has('user'))
        <div id="logout"><button onclick="window.location.href='{{route('/log/logout')}}'">LogOut</button></div>
        @if(session()->get('user')->admin==1)
            @section('title', 'Admin View')
            @section('subtitle', 'MaviApostes Admin')
            <div id="logout"><button onclick="window.location.href='{{route('/uploadJornada')}}'">Pujar Jornada</button></div>
            <div id="logout"><button onclick="window.location.href='{{route('/resultats')}}'">Intr. Resultats</button></div>
        @else
            @section('title', 'User View')
            @section('subtitle', 'Benvingut '.session()->get('user')->nom.' a MaviApostes')
            <div id="logout"><button onclick="window.location.href='{{route('/aposta')}}'">Fer Aposta</button></div>
        @endif
    @else

    @section('title', 'View Only')
    @section('subtitle', 'Welcolme to MaviApostes')
        <form method="post" name="form" action="{{route('/log/verification')}}">
            <!--et mante la session-->@csrf
            <div id="divbutton">
                <div id="textbox">Username:</div>
                <input type="text" name="user" value="{{old('user')}}"/>
                <div id="textbox">Password:</div>
                <input type="password" name="pass"/>
                <input type="submit" name="enviar" value="Login"/>
            </div>
        </form>
        <div id="divbutton"><button onclick="window.location.href='{{route('/signup')}}'">Crear Compte</button></div>
        <div id="divbutton"><button onclick="window.location.href='{{route('/recover')}}'">Recuperar Contrasenya</button></div>
    @endif
    @if ($errors->any())
        <div id="error">
            @foreach ($errors->all() as $error)
                <p>- {{ $error }}</p>
            @endforeach
        </div>
    @endif
@endsection
<?php $dia="";$estat=""; ?>
@section('information')
    <form method="post" name="form" action="{{route('/log/selection')}}">
        @csrf
        <select name="jornada" id="jornada">
            <option value="0">Selecciona jornada</option>
            @foreach($jornada as $j)
                <?php
                    $enable="";
                    $dia="";
                    if(Session::get("jornada")==$j->jornada){
                        $enable="selected";
                        $dia=$j->inici;
                        if($j->tancada==0){
                            $estat="Oberta";
                        }else{
                            $estat="Tancada";
                        }
                    }
                ?>
                <option value="{{$j->jornada}}" <?php echo($enable);?>>Jornada {{$j->jornada}}</option>
            @endforeach
        </select>
        <input type="submit" name="sel" id="sel" value="Sel·lecciona"/>
        @if(session()->has('user'))
            @if(session()->get('user')->admin==1)
                @if(Session::has("jornada"))
                    <br><br><input type="submit" name="tanca" id="tanca" value="Tanca Jornada"/>
                @endif
            @endif
        @endif
    </form>
    @if(Session::has("jornada"))
        <br><div id="results">
            <div style="color:white;"><b>D.Inici:</b> <?php echo($dia);?> |  <b>Estat Jornada:</b> <?php echo($estat);?></div>
            <table>
                <tr><th>Local</th><th>Visitant</th><th>Resultat</th><th>Vots Local</th><th>Vots Empat</th><th>Vots Visitant</th></tr>
                @foreach($partits as $p)
                    <tr>
                        <td>{{$p['local']}}</td>
                        <td>{{$p['visitant']}}</td>
                        <td id="vots">{{$p['resultat']}}</td>
                        <td id="vots">{{$p['guanya']}}</td>
                        <td id="vots">{{$p['empata']}}</td>
                        <td id="vots">{{$p['perd']}}</td>
                    </tr>
                @endforeach
            </table>
            @if(!session()->has('user'))
                <button><a target="_blank" href="{{route('/pdf')}}">Generar PDF</a></button>
            @endif
        </div>
    @endif
@endsection
