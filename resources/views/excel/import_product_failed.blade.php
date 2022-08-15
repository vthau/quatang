<?php
if (!isset($arr) || !count($arr)) {
    return;
}

?>

<table>
    @foreach($arr as $ite)
        <tr>
            <td>{{isset($ite[0]) ? $ite[0] : ''}}</td>
            <td>{{isset($ite[1]) ? $ite[1] : ''}}</td>
            <td>{{isset($ite[2]) ? $ite[2] : ''}}</td>
            <td>{{isset($ite[3]) ? $ite[3] : ''}}</td>
            <td>{{isset($ite[4]) ? $ite[4] : ''}}</td>
            <td>{{isset($ite[5]) ? $ite[5] : ''}}</td>
            <td>{{isset($ite[6]) ? $ite[6] : ''}}</td>
            <td>{{isset($ite[7]) ? $ite[7] : ''}}</td>
            <td>{{isset($ite[8]) ? $ite[8] : ''}}</td>
            <td>{{isset($ite[9]) ? $ite[9] : ''}}</td>
            <td>{{isset($ite[10]) ? $ite[10] : ''}}</td>
            <td>{{isset($ite[11]) ? $ite[11] : ''}}</td>
            <td>{{isset($ite[12]) ? $ite[12] : ''}}</td>
            <td>{{isset($ite[13]) ? $ite[13] : ''}}</td>
            <td>{{isset($ite[14]) ? $ite[14] : ''}}</td>
            <td>{{isset($ite[15]) ? $ite[15] : ''}}</td>
            <td>{{isset($ite[16]) ? $ite[16] : ''}}</td>
            <td>{{isset($ite[17]) ? $ite[17] : ''}}</td>
            <td>{{isset($ite[18]) ? $ite[18] : ''}}</td>
            <td>{{isset($ite[19]) ? $ite[19] : ''}}</td>
            <td>{{isset($ite[20]) ? $ite[20] : ''}}</td>
            <td>{{isset($ite[21]) ? $ite[21] : ''}}</td>
            <td>{{isset($ite[22]) ? $ite[22] : ''}}</td>
            <td>{{isset($ite[23]) ? $ite[23] : ''}}</td>
            <td>{{isset($ite[24]) ? $ite[24] : ''}}</td>
            <td>{{isset($ite[25]) ? $ite[25] : ''}}</td>
            <td>{{isset($ite[26]) ? $ite[26] : ''}}</td>
            <td>{{isset($ite[27]) ? $ite[27] : ''}}</td>
            <td>{{isset($ite[28]) ? $ite[28] : ''}}</td>
            <td>{{isset($ite[29]) ? $ite[29] : ''}}</td>
        </tr>
    @endforeach
</table>
