@extends('layouts.apostes')
@section('title', 'Update Results')
@section('subtitle', 'Fes les teves apostes '.session()->get('user')->nom)

@section('navegador')
    <div id="logout"><button onclick="window.location.href='{{route('/log/logout')}}'">LogOut</button></div>
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
    </form>
    @if(Session::has("jornada"))
        <form method="post" action="{{route('/aposta/upload')}}">
        @csrf
            <br><div id="results">
                <div style="color:white;"><b>D.Inici:</b> <?php echo($dia);?> |  <b>Estat Jornada:</b> <?php echo($estat);?></div>
                <table id="taulaResultats">
                    <tr><th>Local</th><th>Visitant</th><th id="vots">Aposta</th><th id="vots">Resultat Partit</th></tr>
                    @foreach($partits as $par)
                        <?php $p=get_object_vars($par); ?>
                        <input type="hidden" name="partit_id[]" id="partit_id[]" value="{{$p['id']}}">
                        <tr>
                            <td>{{$p['local']}}</td>
                            <td>{{$p['visitant']}}</td>
                            <td id="vots">
                                <select name="{{$p['id']}}" <?php if(!empty($p['resultat']) || $estat=="Tancada"){ echo("disabled");}?>>
                                    <option value=""></option>
                                    <option value="1" <?php if($p['aposta']=="1"){ echo("selected");}?> >1</option>
                                    <option value="x" <?php if($p['aposta']=="x"){ echo("selected");}?> >x</option>
                                    <option value="2" <?php if($p['aposta']=="2"){ echo("selected");}?> >2</option>
                                </select>
                            </td>
                            <td id="vots">{{$p['resultat']}}</td>
                        </tr>
                    @endforeach
                </table>
                <br><br><input type="submit" name="pujar" id="pujar" value="Fer Aposta"/>
            </div>
        </form>
    @endif
    @if(Session::has("success"))
        <div id="regform">
            <p style="color:white; margin:0px;">{{Session::get("success")}}</p>
        </div>
    @endif
@endsection
