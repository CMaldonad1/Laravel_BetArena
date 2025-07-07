@extends('layouts.apostes')
@section('title', 'Update Results')
@section('subtitle', 'MaviApostes Admin - Actualitzar Resultats')

@section('navegador')
    <div id="logout"><button onclick="window.location.href='{{route('/log/logout')}}'">LogOut</button></div>
    <div id="logout"><button onclick="window.location.href='{{route('/uploadJornada')}}'">Pujar Jornada</button></div>
    <div id="logout"><button onclick="window.location.href='{{route('log')}}'">Back</button></div>
@endsection

@section('information')
    <form method="post" name="form" action="{{route('/log/selection')}}">
        @csrf
        <select name="jornada" id="jornada">
            <option value="0">Selecciona jornada</option>
            @foreach($jornada as $j)
                <?php
                    $enable="";
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
        @if(Session::has("jornada"))
            <br><br><input type="submit" name="tanca" id="tanca" value="Tanca Jornada"/>
        @endif
    </form>
    @if(Session::has("jornada"))
        <form method="post" action="{{route('/resultats/upload')}}">
        @csrf
            <br><div id="results">
                <div style="color:white;"><b>D.Inici:</b> <?php echo($dia);?> |  <b>Estat Jornada:</b> <?php echo($estat);?></div>
                <table id="taulaResultats">
                    <tr><th>Local</th><th>Visitant</th><th id="vots">Resultat</th></tr>
                    @foreach($partits as $p)
                        <input type="hidden" name="partit_id[]" id="partit_id[]" value="{{$p['id']}}">
                        <tr>
                            <td>{{$p['local']}}</td>
                            <td>{{$p['visitant']}}</td>
                            <td id="vots">
                                <select name="{{$p['id']}}">
                                    <option value=""></option>
                                    <option value="1" <?php if($p['resultat']=="1"){ echo("selected");}?> >Victoria </option>
                                    <option value="x" <?php if($p['resultat']=="x"){ echo("selected");}?> >Empat    </option>
                                    <option value="2" <?php if($p['resultat']=="2"){ echo("selected");}?> >Derrota  </option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <br><br><input type="submit" name="pujar" id="pujar" value="Actualitzar Resultats"/>
            </div>
        </form>
    @endif
    @if(Session::has("success"))
        <div id="regform">
            <p style="color:white; margin:0px;">{{Session::get("success")}}</p>
        </div>
    @endif
@endsection
