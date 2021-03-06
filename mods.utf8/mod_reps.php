<?php
/*
+--------------------------------------------------------------------------
|   Invision Power Board v2.1.2
|   =============================================
|   by Matthew Mecham
|   (c) 2001 - 2005 Invision Power Services, Inc.
|   http://www.invisionpower.com
|   =============================================
|   Web: http://www.invisionboard.com
|   Time: Fri, 14 Oct 2005 18:51:31 GMT
|   Release: 50690ede8a42052b7a1400c0a925a711
|   Licence Info: http://www.invisionboard.com/?license
+---------------------------------------------------------------------------
|   > $Date: 2005-10-10 14:03:20 +0100 (Mon, 10 Oct 2005) $
|   > $Revision: 22 $
|   > $Author: matt $
+---------------------------------------------------------------------------
|
|   > MODULE FILE (EXAMPLE)
|   > Module written by Matt Mecham
|   > Date started: Thu 14th April 2005 (17:59)
|
+--------------------------------------------------------------------------
*/

//=====================================
// Define class, this must be the same
// in all modules
//=====================================
if ( ! defined( 'IN_IPB' ) )
{
        print "<h1>НЕ ЛЕЗЬ КУДА НЕ НАДО</h1>Этот файл так нифига не вызовеш. Заходи через форум.";
        exit();
}


class module
{
        //=====================================
        // Define vars if required
        //=====================================

        var $ipsclass;
        var $class  = "";
        var $module = "";
        var $html   = "";
        var $result = "";

        //=====================================
        // Constructer, called and run by IPB
        //=====================================

        function run_module()
        {
            function sectostr($secs)
            {
                $st="";
                $tmp=floor($secs / 86400);
                if ($tmp>0) {$st='<span id=\'days\'>'.$tmp.'</span> д. ';}
                $tmp2=floor(($secs - ($tmp*86400))/3600);
                if ($tmp2>0) {$st=$st.'<span id=\'hours\'>'.$tmp2.'</span> ч. ';}
                $tmp3=floor(($secs - ($tmp*86400) - ($tmp2*3600))/60);
                if ($tmp3>0) {$st=$st.'<span id=\'mins\'>'.$tmp3.'</span> м. ';}
                $tmp=floor($secs - ($tmp*86400) - ($tmp2*3600)-($tmp3*60));
                if ($tmp>0) {$st=$st.'<span id=\'secs\'>'.$tmp.'</span> сек. ';}
                return $st;
            }
                //=====================================
                // Do any set up here, like load lang
                // skin files, etc
                //=====================================

                $this->ipsclass->load_language('lang_boards');
                $this->ipsclass->load_template('skin_boards');

                //=====================================
                // Set up structure
                //=====================================
               $this->ipsclass->DB->query("select field_4 from ibf_pfields_content where member_id=".$this->ipsclass->member['id']."");
               $frows = $this->ipsclass->DB->fetch_row($fquery);
               if (( $this->ipsclass->member['mgroup'] == $this->ipsclass->vars['admin_group'] )or($frows['field_4']=='y'))
               {
                       $html=$html. "
                       <script type=\"text/javascript\">
						function confirmation()
						{
						var answer = confirm(\"Опубликовать текущую игру?\");
						if (answer)
						{
							window.location = \"{$this->ipsclass->base_url}act=module&module=shgames&cmd=pub\";
						}
						else
						{
							alert(\"Хорошо, не будем!\")
			   			}
						}
						</script>
                       <div id=\"userlinks\">
                       <p class=\"home\"><b>Администрирование Cхватки:</b></p>
                       <p>
                       <font color=red><b>Перед игрой:</b></font>
                       <a href='{$this->ipsclass->base_url}act=module&module=reps&cmd=addg' title='Настройки предстоящей(текущей) игры.
Здесь вы можете создать игру, удалить игру или отредактировать название, дату и время и призовой фонд игры.'>Настройки игры</a> &middot;
                       <a href='{$this->ipsclass->base_url}act=module&module=reps&cmd=scn' title='Здесь вводится и редактируется сценарий. Можно использовать HTML.'>Редактор сценария</a> &middot;
                       <a href='{$this->ipsclass->base_url}act=module&module=reps&cmd=deng' title='Здесь можно отметить кто зарегистрирован на предстоящую игру. Галочка есть - зарегистрирован.'>Зарегистрировать на игру</a><br>
                       <font color=D2CB03><b>Во время игры:</b></font>
                       <a href='{$this->ipsclass->base_url}act=module&module=reps&cmd=spy' title='Во время игры здесь можно смотреть какая команда на каком уровне и подсказке, и когда она начала этот уровнь.'>Шпиён</a> &middot;
                       <a href='{$this->ipsclass->base_url}act=module&module=reps&cmd=exmsg' title='Во время игры здесь можно отправить ЭКСТРЕННЫЕ сообщения командам.'>Сообщение</a><br>
                       <font color=green><b>После игры:</b></font>
                       <a href='{$this->ipsclass->base_url}act=module&module=reps&cmd=tlevs' title='Здесь находится таблица завершения командами уровней с указанием времени и отставания от лидера.'>Финиш по уровням</a> &middot;
                       <a href='{$this->ipsclass->base_url}act=module&module=reps&cmd=logs' title='Тут находятся логи игры.'>Логи</a> &middot;
                       <a href='{$this->ipsclass->base_url}act=module&module=reps&cmd=ochki' title='Здесь начисляются очки. Очки добавляются к уже имеющимся. Чтобы снять очки нужно задать отрицательное число.'>Очки</a> &middot;
                       <a href='JavaScript:confirmation();' title='Здесь можно опубликовать текущую завершенную игру(работает только со включенными JavaScript)'>Опубликовать игру</a>
                       </p>
                       </div>
                       <br>
                       ";
                       switch( $this->ipsclass->input['cmd'] )
                       {
                               case 'logs':
                                     $this->logs($this->ipsclass->input['id']);
                                     break;
                               case 'scn':
                                     $this->scn();
                                     break;
                               case 'spy':
                                     $this->spy();
                                     break;
                               case 'tlevs':
                                     $this->tlevs();
                                     break;
                               case 'deng':
                                     $this->deng();
                                     break;
                               case 'addg':
                                     $this->addg();
                                     break;
                               case 'ochki':
                                     $this->ochki();
                                     break;
                               case 'exmsg':
                                     $this->admexmsg();
                                     break;
                               default:
                                     $this->logs("");
                                     break;
                       }

                       $html=$html.'<font size=2>'.$this->result.'</font>';
                       $this->ipsclass->print->add_output( $html );
                       $this->nav[] = "<a href='{$this->ipsclass->base_url}act=module&module=shvatka'>СХВАТКА</a>";
                       $this->ipsclass->print->do_output(array(OVERRIDE => 0, TITLE => 'Админка игры', NAV => $this->nav));
               }
               else
               {
                       $html=$html."Вы не администратор";
                       $this->ipsclass->print->add_output( $html );
                       $this->ipsclass->print->do_output(array(OVERRIDE => 0, TITLE => 'Вы не администратор'));
               }
               exit();
        }

        //------------------------------------------
        // do_something
        //
        // Test sub, show if admin or not..
        //
        //------------------------------------------
      function admexmsg()
      {      	    $res='';
      	    if ((isset($this->ipsclass->input['msgtxt'])and($this->ipsclass->input['msgtxt'])!=''))
      	    {	           {
	              if (isset($this->ipsclass->input['ig'])&(isset($this->ipsclass->input['msgexptm'])))
	               {                     $this->ipsclass->DB->query("INSERT INTO ibf_sh_admin_msg (msg, starttime, endtime,  komand,  autor, hash) VALUES ('".($this->ipsclass->input['msgtxt'])."','".(mktime())."','".(mktime()+($this->ipsclass->input['msgexptm']*60))."','".(implode ( ',',  $this->ipsclass->input['ig']))."','".($this->ipsclass->member['name'])."','".(md5( $this->ipsclass->input['msgtxt']))."')");
	                 $res.='<br>Сообщение отправлено.';
	               }

               }
      	    }
            $res.='<form "./index.php" method="post">
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="reps">
<input name="cmd" type="hidden" value="exmsg">';
            $size=mysql_num_rows($this->ipsclass->DB->query("select * from ibf_sh_comands where dengi=1"));
            $res.='<TABLE><tr><td><i>Выберите команды, для которых<br>предназначено сообщение:</i></td><td style="vertical-align:bottom;" align="center"><i>Текст сообщения</i></td></tr><tr><td style="vertical-align:top;"><select style="width:100%;" multiple size="'.$size.'" name="ig[]">';
            while ($frows = $this->ipsclass->DB->fetch_row($fquery))
            {      	       $res.='<option value="'.$frows['nazvanie'].'">'.$frows['nazvanie'].'</option>';
      	    }
      	    $res.='</select></td><td><textarea name="msgtxt" rows=5 cols=50 wrap="off"></textarea></td></tr><tr><td align="center" colspan=2>Время существования сообщения в минутах:<input name="msgexptm" type="text" value="3"></td></tr><tr><td align="center" colspan=2><input type="submit" value="Послать сообщение"></td></tr></table></form>';
            $this->result=$res;

      }
      function logs($id)
      {
                 $chisla=array('0','1','2','3','4','5','6','7','8','9');
                 $res="";
                 $mv=$this->ipsclass->input['move'];
                 $keyprev="";
                 if (($id!="")and(!in_array(substr($id,0,1),$chisla)))
                 {
                      $id="";
                 }
                 $comd=array();
                 if (($id!="")and($this->ipsclass->DB->query("select * from ibf_sh_comands where n='".$id."'")))
                 {
                     $this->ipsclass->DB->query("select * from ibf_sh_comands where n='".$id."'");
                 }
                 else
                 {
                     $this->ipsclass->DB->query("select * from ibf_sh_comands");
                 }
                 while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 {
                   $comd[$frows['n']]=$frows['nazvanie'];
                 }
                 if (!$mv)
                 {$res=$res.$mv.'<div class="borderwrap"><div class="maintitle" align="center">Логи</div><br>';}
                 foreach ($comd as $n=>$naz)
                 {  $keyprev="";
                    if (mysql_num_rows($this->ipsclass->DB->query("select * from ibf_sh_log where comanda='".$naz."' order by time"))!=0)
                    { if ($mv)
                      {$res=$res.'<table cellspacing="1" class="borderwrap" style={width:auto;}><tr><td align="center" colspan="3" class="maintitle">'.$naz.'</td></tr>';}
                      else
                      {$res=$res.'<table cellspacing="1" class="borderwrap" style={width:auto;} align="center"><tr><td align="center" colspan="3" class="maintitle">'.$naz.'</td></tr>';}
                      $res=$res.'<tr><th align="center" >Время</th><th align="center" >Ключ</th><th>Автор</th></tr>';

                      while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                      {
                         if  ($keyprev!=$frows['keytext'])
                         {
                             $res=$res."<tr class='ipbtable'><td class=\"row1\">".($frows['time'])."</td><td class=\"row2\" align=\"center\">".html_entity_decode($frows['keytext'])."</td><td class=\"row2\" align=\"center\"><b>".($frows['autor'])."</b></td></tr>";
                             if  ($frows['levdone']!='0')$res=$res."<tr class='ipbtable'><td id=\"gfooter\" colspan=3 align=\"center\">Уровень ".$frows['levdone']." закончен</td></tr>";
                         }
                         $keyprev=$frows['keytext'];
                      }
                      $res=$res.'</TABLE><br>';
                    }
                  }

                 if ($mv!="")
                 {
                        $this->ipsclass->DB->query("select * from ibf_posts WHERE pid='".$this->ipsclass->input['move']."'");
                        $frows = $this->ipsclass->DB->fetch_row($fquery);
                        $this->ipsclass->DB->query("update ibf_posts set post='".(addslashes($frows['post'].'<br><b>Логи</b><br><br>'.$res))."' WHERE pid='".$this->ipsclass->input['move']."'");
                 }
                 else
                 {$res.='</div>';}
                 $this->result=$res;
      }
      function scn()
      {
                 $sub="{$this->ipsclass->base_url}act=module&module=reps&cmd=scn";
                 $sub1="{$this->ipsclass->base_url}act=module&module=shedit";
                 $ref=$_SERVER['HTTP_REFERER'];
                 if ((strpos(trim($ref),trim($sub))===FALSE)&&(strpos(trim($ref),trim($sub))===FALSE))
                       {
                          $this->ipsclass->DB->do_insert( 'admin_logs', array(
                                                                                'act'        => 'Адм.Схватки',
                                                                                'code'       => 'Вход',
                                                                                'member_id'  => $this->ipsclass->member['id'],
                                                                                'ctime'      => time(),
                                                                                'note'       => 'Просмотр сценария. Пришел с '.$ref,
                                                                                'ip_address' => $this->ipsclass->input['IP_ADDRESS'],
                                                          )       );
                       }
                 $res="";
                 $ptm="";
                 if ($this->ipsclass->input['delg']=="1")
                 {                 	$this->ipsclass->DB->query("delete from ibf_sh_game");
                 	$this->ipsclass->DB->query("select * from ibf_sh_games WHERE status='п'");
                     $frows = $this->ipsclass->DB->fetch_row($fquery);
                 	$res.='<div align="center">Сценарий очищен.</div>';
                 }
                 $b=false;
                 $res=$res.'<div class="borderwrap"><div class="maintitle" align="center">Редактор сценария</div>';
                 $res.="<table style={width:100%;}>
<tr class=\"ipbtable\"><td class=\"row1\">";
                 $this->ipsclass->DB->query("select * from ibf_sh_game order by uroven, n_podskazki");
                 while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 {   $b=true;
                     if ($frows['n_podskazki']!='0')
                     {
                             $res=$res.'<b>Подсказка №'.$frows['n_podskazki'].' ('.$ptm.' мин.)</b>';
                             if ($frows['p_time']=='0')
                             {
                                 $res.=' Последняя подсказка уровня.';
                             }
                             $res.='<br>';
                     }
                     else
                     {
                             $res=$res.'<br><center><b>Уровень '.$frows['uroven'].'. ';
                             $res=$res.'Ключ: </b>'.$frows['keyw'].'';
                             if ($frows['b_keyw']!='') $res=$res.'<b>  Мозговой ключ: </b>'.$frows['b_keyw'];
                             $res=$res.'</center><br>';
                     };
                     $res=$res.$frows['text'].'<br>';
                     if ($frows['p_time']!='0')
                     {
                             $ptm=$frows['p_time'];
                     }
                     else
                     {
                             $ptm="";
                     }
                     $res.='
<div align="right"><form action="./index.php" >
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="shedit">
<input name="lev" type="hidden" value="'.$frows['uroven'].'">
<input name="npod" type="hidden" value="'.$frows['n_podskazki'].'">
<select size="1" name="cm" style={border:1px;border:outset;border-color:#ffffff;font-size:9px;}>
<option value="1">Редактировать</option>
<option value="2">Удалить</option>
</select>
<input  type="submit" value="ОК" style={background:#D2D0D0;border:1px;border:outset;border-color:#ffffff;font-size:9px;></form></div>
</td></tr><tr class=\'ipbtable\'><td class="row1">';
                 }
                 $res.='
<center><form action="./index.php">
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="shedit">
<input name="cm" type="hidden" value="3">
Добавить уровень<input name="lev" type="text" SIZE="2" value="">
подсказка<input name="npod" type="text" SIZE="2" value="">
<input type="submit" value="ОК"></form><br>';
if ($b)
$res.='
<form id="fdelgame" action="./index.php" onsubmit="return confirm(\'Уверены что хотете удалить ВЕСЬ сценарий?\')">
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="reps">
<input name="cmd" type="hidden" value="scn">
<input name="delg" type="hidden" value="1">
<input type="submit" value="Удалить нафиг весь сценарий (кроме загруженных файлов)."></form><br>';
$res.='</center></td></tr></table></div>';
                 $this->result=$res;
      }

      function spy()
      {
               $res="";
               $res=$res.'<div class="borderwrap"><div class="maintitle" align="center">Кто как идёт</div>';
               $res=$res.'<table cellspacing="1" align="center" style={width:100%;}>
<tr class=\'ipbtable\'><th align="center">Название команды</th><th align="center">Текущий уровень</th><th align="center">№ подсказки</th><th align="center">Время начала уровня</th></tr>';
               $this->ipsclass->DB->query("select * from ibf_sh_comands where dengi=1 order by uroven DESC, podskazka DESC");
               while ($frows = $this->ipsclass->DB->fetch_row($fquery))
               {
                     $res=$res.'<tr class=\'ipbtable\'><td class="row1"><b>'.$frows['nazvanie'].'</b></td><td class="row2" align="center">'.$frows['uroven'].'</td><td class="row2" align="center">'.$frows['podskazka'].'</td><td class="row2" align="center">'.$frows['dt_ur'].'</td></tr>';
               }
               $res=$res.'</table></div>';
               $this->result=$res;
      }
      function tlevs()
      {          $res="";
                 $comd=array();
                 $levkeys=array();
                 $tab=array();
                 $res="<br><b>Таблица проходжения уровней</b><br><br>";
                 $levkeys=array();
                 $this->ipsclass->DB->query("select * from ibf_sh_comands");
                 while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 {
                   $comd[$frows['n']]=$frows['nazvanie'];
                 }
                 $this->ipsclass->DB->query("select * from ibf_sh_game where n_podskazki=0 order by uroven");
                 while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 {
                   $levkeys[]=$frows['uroven'];
                 }
                 foreach ($comd as $n=>$naz)
                 {
                    $fin=array();
                    $fq=$this->ipsclass->DB->query("select * from ibf_sh_log where (comanda='".$naz."')and(levdone>0) order by levdone");
                    while ($frows = $this->ipsclass->DB->fetch_row($fq))
                    {
                         $fin[$frows['levdone']]=$frows['time'];
                    }
                    if (count($fin)!=0) {$tab[$naz]=$fin;}
                 }

                 foreach ($levkeys as $lev)
                 {
                    $maxl=strtotime("now")+10000000;
                    $i=0;
                    $j="";
                    foreach ($tab as $naz=>$fin)
                    {
                        if (($fin[$lev]!=0)and(strtotime($fin[$lev])<$maxl))
                        {
                            $maxl=strtotime($fin[$lev]);
                            $i=$lev;
                            $j=$naz;
                        }
                    }
                    if (($i!=0)and($j!=""))
                    {
                         foreach ($tab as $naz=>$fin)
                         {
                              if  ($naz!=$j)
                              {
                                     if ($tab[$naz][$lev]!=0)
                                     $tab[$naz][$lev]=(date('H:i:s',strtotime($tab[$naz][$lev]))).'<br>-'.(sectostr(strtotime($fin[$lev])-strtotime($tab[$j][$i])));
                                     else
                                     $tab[$naz][$lev]='--:--:--';
                              }
                         }
                         $tab[$j][$i]='<font color=red>'.(date('H:i:s',strtotime($tab[$j][$i]))).'</font>';
                    }
                 }
                 $res.='<table class="borderwrap" style={width:auto;}><tr><th>Название команды</th>';
                 foreach ($levkeys as $lev)
                 {
                        $res.='<th> Уровень '.$lev.'</th>';
                 }
                 $res.='</tr>';
                 foreach ($tab as $naz=>$fin)
                 {
                        $res.='<tr class=\'ipbtable\'><td class="row1"><b>'.$naz.'</b></td>';
                        foreach ($levkeys as $lev)
                        {
                                $res.='<td class="row1" align="center">'.$fin[$lev].'</td>';
                        }
                        $res.='</tr>';
                 }
                 $res.='</table><br>';
                 if ($this->ipsclass->input['move']!="")
                 {
                        $this->ipsclass->DB->query("select * from ibf_posts WHERE pid='".$this->ipsclass->input['move']."'");
                        $frows = $this->ipsclass->DB->fetch_row($fquery);
                        $this->ipsclass->DB->query("update ibf_posts set post='".(addslashes($frows['post'].$res))."' WHERE pid='".$this->ipsclass->input['move']."'");
                 }
                 $this->result=$res;
      }
      function addg()
      {
               $res="";
               if ($this->ipsclass->input['y']=="")
               {
                   if (mysql_num_rows($this->ipsclass->DB->query("select * from ibf_sh_games where status='п'"))!=0)
                   { // редактирование данных игры
                     $frows = $this->ipsclass->DB->fetch_row($fquery);
                     $res.='<center><form action="./index.php">
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="reps">
<input name="cmd" type="hidden" value="addg">
<input name="y" type="hidden" value="1">
<b>Название игры <b><input name="gn" size="100" type="text" value="'.$frows['g_name'].'"><br>
Дата игры в формате ГГГГ-ММ-ДД ЧЧ:ММ:СС <input name="gt" type="text" value="'.$frows['dt_g'].'"><br>
То, что идёт после слов "Призовой фонд игры составляет:" (например: "100 руб", или "сколько собрали") </b><input name="gf" type="text" value="'.$frows['fond'].'"><br>
<input type="submit" value="Подтвердить изменения" style={background:#D2D0D0;border:1px;border:outset;border-color:#ffffff;font-size:11px;}>
</form>
<br>
<br>
<form "./index.php">
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="reps">
<input name="cmd" type="hidden" value="addg">
<input name="delg" type="hidden" value="1">
<input name="y" type="hidden" value="1">
<input type="submit" value="Удалить игру" style={background:#ff9090;border:1px;border:outset;border-color:#ffffff;font-size:11px;}><br></b>
</form><font color=red size=1>После удаления игры все команды и игроки переводятся в состояние "деньги НЕ сдал" </font></center>';
                   }
                   else
                   { // добавление новой игры
                       $res.='<center><form action="./index.php">
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="reps">
<input name="cmd" type="hidden" value="addg">
<input name="y" type="hidden" value="1">
<b>Название игры <input name="gn" size="100" type="text" value=""><br>
Дата игры в формате ГГГГ-ММ-ДД ЧЧ:ММ:СС <input name="gt" type="text" value="0000-00-00 23:00:00"><br>
То, что идёт после слов "Призовой фонд игры составляет:" (например: "100 руб", или "сколько собрали") </b><input name="gf" type="text" value=""><br>
<input type="submit" value="Создать игру" style={background:#D2D0D0;border:1px;border:outset;border-color:#ffffff;font-size:11px;}><br>
</form>
<font color=red size=1>После создания новой игры логи предыдущей игры, а также даты и времена окончания командами педыдущей игры будут стёрты. Если их не запостили пердыдущие организаторы, сделайте это вы. </font></center>';
                   }
               }
               else
               {  //обработка прееданных данных
                   if ($this->ipsclass->input['delg']!="1")
                   {  //ввод данных игры
                       if (mysql_num_rows($this->ipsclass->DB->query("select * from ibf_sh_games where status='п'"))!=0)
                       {
                             $this->ipsclass->DB->query("update ibf_sh_games set g_name='".($this->ipsclass->input['gn'])."', dt_g='".($this->ipsclass->input['gt'])."', fond='".($this->ipsclass->input['gf'])."' where status='п'");
                             $res.='Изменения в настройки игры внесены.<br><br>';
                       }
                       else
                       {
                             $this->ipsclass->DB->query("update  ibf_sh_comands set uroven=0, podskazka=0, dt_ur='0000-00-00 00:00:00'" );
                             $this->ipsclass->DB->query("delete from ibf_sh_log");
                             $this->ipsclass->DB->query("INSERT INTO ibf_sh_games (g_name, dt_g, status, fond) values ('".($this->ipsclass->input['gn'])."', '".($this->ipsclass->input['gt'])."', 'п', '".($this->ipsclass->input['gf'])."')" );
                             $this->ipsclass->DB->query("ALTER TABLE ibf_sh_log PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =1");
                             $res.='Игра создана.<br><br>';
                       }
                   }
                   else
                   {  //удаление игры
                       $this->ipsclass->DB->query("update  ibf_sh_comands set uroven=0, podskazka=0, dengi=0, dt_ur='0000-00-00 00:00:00'" );
                       $this->ipsclass->DB->query("update  ibf_sh_igroki set ch_dengi=0" );
                       $this->ipsclass->DB->query("delete from ibf_sh_games where status='п'");
                       $this->ipsclass->DB->query("select * from ibf_sh_games order by n desc");
                       $frows = $this->ipsclass->DB->fetch_row($fquery);
                       $this->ipsclass->DB->query("ALTER TABLE `ibf_sh_games` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =".($frows['n']-1));
                       $res.='Игра удалена.<br><br>';
                   }
               }
               $this->result=$res;
      }
      function deng()
      {
                 $all=0;
		   $qut=0;
                 $res="";
                 if ($this->ipsclass->input['y']=="1")
                 {
                  $res.='<center><b>Изменения внесены</b></center>';
                       $fq=$this->ipsclass->DB->query("select * from ibf_sh_igroki");
                        while ($frows = $this->ipsclass->DB->fetch_row($fq))
                        {
                                if ($this->ipsclass->input[$frows['n']]=="1")
                                {
                                        $this->ipsclass->DB->query("update  ibf_sh_igroki set ch_dengi=1 where n=".$frows['n']);
                                }
                                else
                                {
                                        $this->ipsclass->DB->query("update  ibf_sh_igroki set ch_dengi=0 where n=".$frows['n']);
                                }

                        }
                       $fq=$this->ipsclass->DB->query("select * from ibf_sh_comands");
                        while ($frows = $this->ipsclass->DB->fetch_row($fq))
                        {
                               if ($this->ipsclass->input['c'.$frows['n']]=="1")
                                {
                                        $this->ipsclass->DB->query("update  ibf_sh_comands set dengi=1 where n=".$frows['n']);
                                }
                                else
                                {
                                        $this->ipsclass->DB->query("update  ibf_sh_comands set dengi=0 where n=".$frows['n']);
                                }
                        }
                 }
                 $comd=array();
                 $this->ipsclass->DB->query("select * from ibf_sh_comands");
                 while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 {
                   $comd[$frows['n']]=$frows['nazvanie'];
                 }
                 $res.='<div class="borderwrap"><div class="maintitle" align="center">Регистрация на игру </div><br><form action="./index.php">
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="reps">
<input name="cmd" type="hidden" value="deng">
<input name="y" type="hidden" value="1">';
                 foreach ($comd as $n=>$naz)
                 {
                    $res=$res.'<table cellspacing="1" class="borderwrap" style={width:auto;} align="center"><tr><td align="center" colspan="3" class="maintitle"><input name="c'.$n.'" type="checkbox" value="1"';
                    $cmn=$this->ipsclass->DB->fetch_row($this->ipsclass->DB->query("select * from ibf_sh_comands where nazvanie='".$naz."'"));
                    if ($cmn['dengi']==1)
                    {
                             $res.='checked';
                    }
                    $res.='>'.$naz.'</td></tr>';
                    $fq=$this->ipsclass->DB->query("select * from ibf_sh_igroki where komanda='".$naz."'");
                    $res=$res.'<tr><th align="center" width="50%">Ник</th><th align="center" >Статус</th><th align="center" >Очки</th></tr>';
                    while ($frows = $this->ipsclass->DB->fetch_row($fq))
                    {
                       $usID=$this->ipsclass->DB->fetch_row($this->ipsclass->DB->query("select * from ibf_members where name='".($frows['nick'])."'"));
                       $res=$res."<tr class='ipbtable'><td class=\"row1\"><input name=\"".$frows['n']."\" type=\"checkbox\" value=\"1\"";
                       if ($frows['ch_dengi']=='1')
                       {
                             $res.='checked';
                             $qut=$qut+1;
                       }
                       $res.="><b><a href=\"{$this->ipsclass->base_url}showuser=".$usID['id']."\">".($frows['nick'])."</a></b></td><td class=\"row2\" align=\"center\">".($frows['status_in_cmd'])."</td><td class=\"row2\" align=\"center\">".($frows['ochki'])."</td></tr>";
                    }
                    $res=$res."</TABLE><center>Всего зарегистрированых $qut</center><br>";
                    $all=$all+$qut;
                    $qut=0;
                 }
                 $res.='<center>Со всех команд зарегистрировано '.$all.' человек(а) <br>
                        <center><br>';
                 $res.='<center><input type="submit" value="Подтвердить изменения" style={background:#D2D0D0;border:1px;border:outset;border-color:#ffffff;font-size:11px;}></center></form></div>';

                 $this->result=$res;
      }
      function ochki()
      {
               if ($this->ipsclass->input['y']=="1")
               {
                       $fq=$this->ipsclass->DB->query("select * from ibf_sh_igroki");
                        while ($frows = $this->ipsclass->DB->fetch_row($fq))
                        {
                                if (($this->ipsclass->input[$frows['n']]!="")&&($this->ipsclass->input[$frows['n']]!="0"))
                                {
                                        $this->ipsclass->DB->query("update  ibf_sh_igroki set ochki='".($frows['ochki']+($this->ipsclass->input[$frows['n']]))."' where n=".$frows['n']);
                                        $this->ipsclass->DB->query("INSERT INTO ibf_sh_log_ochkov  set komu='".($frows['nick'])."', skolko='".($this->ipsclass->input[$frows['n']])."',  kogda='".date('Y-m-d H:i:s',strtotime("now"))."', ktopoctavil='".($this->ipsclass->member['name'])."'");
                                }
                        }
                        $fq=$this->ipsclass->DB->query("select * from ibf_sh_comands");
                        while ($frows = $this->ipsclass->DB->fetch_row($fq))
                        {
                                if (($this->ipsclass->input['c'.$frows['n']]!="")&&($this->ipsclass->input['c'.$frows['n']]!="0"))
                                {
                                        $this->ipsclass->DB->query("update  ibf_sh_comands set ochki='".($frows['ochki']+($this->ipsclass->input['c'.$frows['n']]))."' where n=".$frows['n']);
                                        $this->ipsclass->DB->query("INSERT INTO ibf_sh_log_ochkov  set komu='".($frows['nazvanie'])."', skolko='".($this->ipsclass->input['c'.$frows['n']])."',  kogda='".(date('Y-m-d H:i:s',strtotime("now")))."', ktopoctavil='".($this->ipsclass->member['name'])."', komand='1'");
                                }
                        }
                        $res.='<center><b>Очки начислены.</b></center>';
               }
               else
               {
                  $res='<div class="borderwrap"><div class="maintitle" align="center">Очки</div><br><form action="./index.php" method="post">
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="reps">
<input name="cmd" type="hidden" value="ochki">
<input name="y" type="hidden" value="1">';
                  $comd=array();
                  $res.='<table cellspacing="1" class="borderwrap" style={width:auto;} align="center"><tr><td align="center" colspan="3" class="maintitle">Начисление очков командам.</td></tr>';
                  $this->ipsclass->DB->query("select * from ibf_sh_comands");
                  while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                  {
                    $comd[$frows['n']]=$frows['nazvanie'];
                    $res.='<tr class=\'ipbtable\'><td class="row1"> Начислить команде <b>'.$frows['nazvanie'].'</td><td class="row1"><input size=3 name="c'.$frows['n'].'" type="num" value="0"> очков</td></tr>';
                  }
                  $res.='</table><br><div class="borderwrap" style={margin:10px}><div class="maintitle" align="center">Начисление очков игрокам</div><br>';
                  foreach ($comd as $n=>$naz)
                  {
                     $res=$res.'<table cellspacing="1" class="borderwrap" style={width:auto;} align="center"><tr><th align="center" colspan="2">'.$naz.'</th></tr>';
                     $fq=$this->ipsclass->DB->query("select * from ibf_sh_igroki where komanda='".$naz."'");
                     while ($frows = $this->ipsclass->DB->fetch_row($fq))
                     {
                        $usID=$this->ipsclass->DB->fetch_row($this->ipsclass->DB->query("select * from ibf_members where name='".($frows['nick'])."'"));
                        $res=$res."<tr class='ipbtable'><td class=\"row1\">Начислить игроку <b><a href=\"{$this->ipsclass->base_url}showuser=".$usID['id']."\">".($frows['nick'])."</a></b></td><td class=\"row2\" align=\"center\"><input size=3 name=\"".$frows['n']."\" type=\"num\" value=\"0\"> очков</td></tr>";
                     }
                     $res=$res."</TABLE><center><br>";
                     $qut=0;
                  }
                  $res.='<center></div><input type="submit" value="Начислить очки" style={background:#D2D0D0;border:1px;border:outset;border-color:#ffffff;font-size:11px;}></center></form></div>';
                }
                $this->result=$res;
      }
}


?>