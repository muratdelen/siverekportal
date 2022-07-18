 <script type="text/javascript">
 var RecaptchaOptions = {
     lang:"tr",
    theme : "white"
 };
 
//var RecaptchaState = {
//    challenge : '03AHJ_VusOmEzNmEzifu1m8Z3knbmLp3yuS0fM8unuHdGmIXF6BBN2R8WJwQKbSNUl6awckIDhmcab4JDNmqy-RnQL2VHMqGP-oDEpUqzGcZR96vwVGShN5hGec73LZrZ9Vq8MUXyGa0sH4Sj2L3DMiwSVD9rFuFqSs7yVhrgAS_qeA2AEZbol2UGgSLw01Vb23tduR1eS38fvp4CrhN715Ai0DndGLO60Hw',
//    timeout : 1800,
//    lang : 'tr',
//    server : 'http://www.google.com/recaptcha/api/',
//    site : '6Lcm8g4TAAAAAIhZUtRLgnoDsU8cEC2neiMbCScS',
//    error_message : '',
//    programming_error : '',
//    is_incorrect : false,
//    rtl : false,
//    t1 : 'Ly93d3cuZ29vZ2xlLmNvbS9qcy90aC9HTy1xSWJjdFpjQ196bTFFQlNRSF92X2FZSkh3T00zYUVOUnBqMUFxa3QwLmpz',
//    t2 : '',
//    t3 : 'bmE4eDIrSm84V1J6OEhWaGhpR3VrK1pXeDBqNXU0dHBWRHc4YUtNbU5Qb1E5c296aUt6cEFFTGl0RmxlUVFDbG5CdW9QaWs4VG9EVS9pM2c5V3lzTUNiYlZZbERaNUtnZ1lmVXNhbUpWL0h0KzZ2MlcvRkMyUkdISm1TcHExM2tBT081VWU0Z0FvTCtwbHh4VVFua3VJRk1wRS9SWmluYkhseUgySmtZcFVMVWJOTXgwME5lSHJyOHZ3NjVCbDQzUU9iSVRNZnhmbEpYbm1EdXU5OW9zdGx5dlhtcHd3aTZEbEdrbTNRWDI5NnY0aFEyanhZQjYyOWJQb01uRjk3OUYwYk5ZT1NMQXpETWxqNGgya0FONXBFRm95bGFsdnR2S2dOdEtnMWFzVTJhZldpcHhpQUhRSDRZVkRwR3hWOUxRa2lBM3RwZ1Z3ZHBCdjJCYTQ0K0ZKd0VCdzYwOGpacmU4a2hCK2d6S2xBbFdzM3ZoYnpRdTlINHQzaEFFWUsyakd1Q0RyTGhwZm5Ud1JJTXVOeU1TYmprOGV3RVphU1BXeVhURXJwYXZIUVc3KzVleFB0TkJmdGtobTB2MW5yZFdkV0Z2TmRsMmFOekdicGJGb21mVk5xelFOQXllbXpjek9IUHBXazJzd1Z0OWhmU1NNcTFFMGRGenFOU21GN0REVUdBaXNTVS9Bckt2ZVZkMjB2REpzck9GdTExY21ndFBPcW5kRDJSMkdCYWFLcnBDS3Y0TG9QaUJHRFRQME9vdlFjWkkyT3ZHQTRHSWhuU01GQ0ZKcnhyUm9XNGJNQUU0UGxWSE5EL1U5ZmZQMkQydWVRZ01LQTQvQ3MrS2tCLzA5YXdodVpaSk5JNHQ4cS9ZYTEvWTJXaDlCam80bS9IU0ZlcUhWZlZoMmcvS0pqYjFUMmNDZUJHOThoKzllN05ZeEdtbEl6R3QyeFJZMFlIdmR4TVlSNHhxbmZMaEF1R3BVKzZFQlg5aDBTUjg5RkdndzFndk12ZDFETGwxTzdCNzAvUWtteUYxdmhuV2xkeHJzREpXZ1A5dDA5UU9QcXJvVU1NV2RlYkNrLzYremZBUndxT3lsM0hIdDZTNFlkZ3ZLMHNzdmtFWElwNy9ZMzJBTGRPb1gwSGJlMldObWFtS1BPWmxkbldLbk1QK0dwVkN2Rzg5NmZCMFNiRUovQWRVN3BmZ3Y4NnF5OXpjOVZaTnpiOGdTQ24zUUpOOUo2SGpoTkMxcGtvb1dNOGgxekhXMWJPb0R2NG5Cd01pZUFoeVhJTGx6cHlHMzRRVllWT2xJQ0VrcHB1T0Y1elJRNDl0aEJDSmNMTjR0QkI0WG82QU1qai8rUUU2TWZyMUxtS1pERWtudm9tb05TY1pmZGVmNlJzejJzRGhrM294cmRJNzZGcTl1ekFLWGFBVTlsNlVDYUloM1NXS1JyMlQ5akhMdVpZblBYMnU2cUp3WmMxcHdKQmVuQWhaUHVSR2JOaFk2d3BrZytwS0lEL3pESWg0eU16RlMrNlFERjhFS1c0emRkUmlRNVR5TDg1RVVrRXhNeEVmYVhwWHQ4ZEYweCs5RjRVMUdKYWpaZFRUdGxHaUR0a2NRV25lZ0RaUFJKYktaWVRLd0thc2NYSmxiNE5VQU9WRTMxUEFkZ2RkQmc2UlpobHNPVTJ0TkRieDZTMXRQRFZndWRVR1o1M2ZuNHhVeEZXd2NKWUNRMjV2NWlwUXp1QXRBK2E1R3JDUGd2ZEJoR0NYQUZ5SUZkcGVQQ2RkSkNqRmtoNVgvcDR3UFMvTGlqZFlBalJzdGw3NG55OTJnTXdvcFhnTDI1M0FXZmxqTStWVW0wUnJwSjhISU5GaDhMRDlaOWIyc3NHakR2RHZpMzFHUXh4UkhZT0RkaGlwdXVGVDFyRlhjUEtPbEp1Y1JPcVNoYzNXRXJkV1JzcG5CRnJuUS85QmZYbXVOR1VKbC9XMHNHUVBKSm5ST2FJeGhTTFFoc0NRNUhzRXBCNldqTWVBeTBUYXgwQ0ErRWhaeWs4TXNmZkRqa1J6WGhEVmpweWl5NVdzZ3Z1NjFEV3BQckFseUVsU0dlVkFGWEc0SEg4REVCa2N5a2pwM1pqUHNnb0g3REhMa01pM2ppcWVKM0l5d0IrT0RSS0hiN0pvVnQwMVR6MnlGYXdYN0w5bWZOUE41MFZKTGNtUWtZQ3ovOCtxRVR0TmtPUk1BbTJZM3l3bVdlM0lKK0tBYUR0S2dLVml4U2lUSE15OVJHT1ZGUXNZSW8rUFVEMXJnY1YwNS83TXNpckNzVG5iakJ6dmdyWTlENllMOExTYTdnNmJxOXA3ZFl1Ukhyb3VYUGF3cEdLZGNkSDkvSVdHb29abVNlYkF2UDlRc01BZzh4aHJEOUhiTzlEYUNzUTBxUldkY1V1WnhxVHV6TVF5SnlTNzc4Mmp2MFFBME5KckZBbHNJdDJrd01Vckx4b0FBRHVXdjdwdVVvMk1BVVlTZWlNMGg4OStaS3FBSGFhejZtZW5Wb1dwMjBwU1dyNVVDZStMOXlkMHhNbkd1em0ycWl2OWtXMEJVN3hCMjRWNHBDVDdES3Yrd2dpU3Y4M1o2UU1kUDQ2VWtrZ1FwTFhOVkFLSFdsOHIrcnNxQ2Zzd05iZlBRWnZ0NzVZT3FQcnorVXcrTVF2RU1ZOEswVWFYUEVhdTVvU0JQK05SKytOM0FieVlyUzNGMkVXSWlnMlRVcDFieENGY1hzSE9wNExwdjZuSVR6YlhVZE1qckt2dTV0N0hlRmxPY3kwTEdsRzhnVHpyZDVvUU5jK05VM0lrbWkwNWF5ciszeTQvaUlZMHVYWDZOdExGNHVCTUFIYXRBdzlTeCtXY2I2NXgybUh1TDRqLzcxY3Y0TW9ianlyVkt2bmJJd3NLMmhocHZtOTVBS3p3S2k5RUVtazVXbUhndHdHT3hQbk1yTzV3OENidzkxMkdKb2x4cCtRcnJFWmRFYnhybFZUcWpldk92Qm5NMWVXZm1vaGtaRmsyZk12dENXbXRPVk5oSnpLeGplMXhGdS84UXYwSjVYbW4rS0JVMnZ0aHRYTkphdEQ0Tm5OTnUrMEJMUjZ1V3loVXJvQ0wyd216SUxya0pQTTFUYWxpMHFKc0RlY0szYXc4eGRWL2NoMXUrbi9iS2s1ci9hZWNrd0ZpK3NtdVdRSThsWnM1YytUVUhUSUh3SHJDTnJFaVptbXJ6ay9XQndaak1FQWhzZzVhTUNFQlI1N1hmU1BwOEJ0YzFxa3BBK3NDbmFqeVZKdStWOXR5TENFTVJDdW5MbmlndW9VQmZ1enB2c2gzbGl6T3BYRkVJWHpCd0wyaGc2alJ3RFBNb1F1eDNSRlVwdmlrZWs3L0NxbkJhOW8zZHZhaEx5OVc0dzJuSHY0ZEFvZm1rN2NMRUVYMVd3Q3JFNEJISVFFUjFvY3A4OXZhdlJyY2dtd1owQ0QrWkM3cGJ1bGYxZVRzV0RTb01TbjAyQW1FZUpKVmpUVGNlR29QaXZmajlXRmRXV2VIZGNwUms4b2xmQmpyby9ocnN1S1I1MHNDaWt4S1JwR3NMWTJQNnduNmxwWXI0RnRxOEJGSDBmN1ZYbzR1b2FlR3ZySHlJWWYza09SakhvdFcySGVleDJOSmN3OTloWnhobUo4NDBINk1Sd0FGd2JzY2t5Wmx3OGpxOS9tRjJzLzRrcFNlNFkwR0YrQnB6aGNFb2VxSXAwaXE5Wk1DbXArclFWaGUyUDZubWczYmE4cTAxa1RWNGVCbnZoejdLWGxaTzNrYXIvVHBNODJ3a0lXVjFGV3BuMGtpVVltZDBTblZRWGN1UmtYZ0pHZ3QwL3QvdXhoenFZU3ZhSFF2UWlHYUppNWU1MllVN3I4MitHYlo4RTNXL3lDUWVtak1Td3Q3WU8zTEdrcHMxU1JyQWhtaTRVbEs5NStVTzRnV2pmTFNhK1NRTjdCK0RjTXFEemhOS1JpWlRJSitKSEd1T29CcmJIL29TMjhpUUpEMkwzaGlVR21aSk5EdVF3VmhsQmVveHpTL1g4ajBSQ204U2YxTmFEVDRzQmhMR3RHS21VbEFlUGxaUm5iUE9UTWtJeEc5U0F2b2VUdVNlUkNQTDhTZ1FxMzJhVVhvbW5xRWIyQzkxa1RXTVhrL01HREI3M2tIZWMxOEpIb0UwdXIzMThTN2d1ZGtoQzM5WXB3dlZxWmNjQ3BZNHNrd3hKaTRhNFRSd09ramlMemFYeFFueEtRekJvTlR4VjArTG1JalJuWnY0UEkra3Z4dVlka0hOM2xUcVZUUERFcTFwR3lyblZKVXlWY21PLzB5WkVNL1F6N2tXUmd3TUdDU0pYbFJwdlUyckRGMFlLWFVRa0lYTi8rL1Y4NFJoRlZTTlBCS2dVNmxQdnczNlJoc3EvaldjRWdrVVdSaWFpMWROY0RoRko5Vyt0a0ZncVladGhrOEdkT2lseDl2OHM2TEVQRHc5Z2tvRXpPaG1CNVo0amdaUjVUb1dtOWs1cmRmVDVGT3RIbURUa0lFN0hHMDVyUE05TWFiVnFNVVdrQUFtR2Zvb3prYU9OZ0x2WE84TWFDNUhGdmFqZFBjQWxmSTFnN0VqQzM2V3hna2ZRc2hteVh3SUcwZjNuM0ZOeVV4RjZWOFZLUlZObFZqOXJXS3VVREtwUzU1Q3U1YUsrZFRnQmVYQTk3aTNuWSs2RjR4MDRqZVZvZE9xWTFDQ3E0RjJyc0lhMk9uY2hWV0lRSlp2Q2FlM1pLSXBSWDc5UjhadHJRWktpb09UZ2hndEQzS3BKYkJ6RG05alRTSnlHQnJYeE5jVHNOTkcyUE1yZEZTZXROR0FvSUU5YzUrRzB2SnpsaDZsQzdGaWlKa09rWHRNL2Z3eWdCUTU5T0dBd05QaHFvL0FURVhrc2lUWCt0QXRuaWpnYlU3RVVidGVreURDeG1Ueno5eDVuUlVESFFSYjFCZjlTNFZmVlY5azJybmxPZGZvL3RZeVB6TUo2cUtueXZHUUVwYzVKWkEzQkg5Y3J5MHhrMmRtaHB5RnJnVjRqbEhOb2M3Y2VtZ3pKeTVxZ1pHWTB4VU1SN0pMQ1ZWd1RCREZObGc4SFk3dENNb0U5T1Z1YWtLbzhxcDZCdy83UC9temw3blVzZWpleDh1OFlrOHNQcHBhUUQ4MWtHUVJ5YmtIdHVzN3UycS9scW10alBqSWJFYVpDY2ZxVnpHdFYwR0RTbGh2WTZwbitFa3BwdVVRb1NURGVoNXBkZ1haWExaOURwYit6SFdDTmtpN0djTEJseDFwK1libURLMExRQjllQS9EaFVNdTVSN2xLN3JSbFhDUHNCeXVXK1gxWXJkN0ExRlhxWFJYZXFDNEUrNjVYdWFKLzZyMytGdldVUmFzWDhIems1ZDVjTWZDVkJsd056a3Q5VGR1WW8rZ3Q0ZTRzQ2YxSWMwS2tUOVF1RXBjYW5oTFZldjhtaTcyRXRKREhDejB0TEJ5cDBQbmErYUNzcy84OUZoSVVFclEyVUZCdU5GK0pBRmR2UkZDQW00N3dYMUNDQ0szOThtczNNVW9TYVIvOUNyYUttUWtSV3I1UFcvSEh0N2Qxa2lKZTJmZWk0ZXVHeWFqSWZlSkU0cmV0SWIrM01KUXdlcWV3UWcyTFdyR3NIangyczZqQ01uNTFqeWFzblBNa1NyZTVHblp5QnZyRmxCL0lKUUZuM2psbmUzNERqQ1NQdVJDWHBpRHE3eUtHMXl0QXpMTE0wazdKZjdpcFdYMDd2dExKSUlwM25MaXFmM3Bibk5Ca2lBQlV1VFRJdm9jempZQVN4NDBUdzIwTTdFbk5na0tjb0t0VFp6LzJTTnVmWktlTUtOUTNPaUxzQ0UrUTBsS2M3RHJXV1JreWNDaEVka0lkK3ZRSU85aHFWOU84VS9PVFdHNkR4M0J3VkxmbGxsYTFjb0hqR0JKVTJxK25iR1VPSDRtWkdGQmEzTTRsbWpsOVJsem91ZTBQSy9JRkhUUGlneTBJaHlwNnlLb2l1K1FjanNNbkNsZUROeXBiVG5HMUZUU0FxUTZhVUowb1pYbHZkdllqT2NTeE4vWGN0UnJyN1dPN2xtL2J2STE0QWZsNS8vZnVTMDhMdFhRY2FveUJSTW92Z0psdU41OVFGOHdiS1lxWHFEb2JYYlBhem9VdzkwNlQzYlJHeG9nOVpFSWU4bk54Z3RqRGJWaUtwa256Y090bnlKZWNleTFBUGVzVjFscTdkbkJoYmt2ZkNYeHFrY08yK0tBZkFWcDU1R2lGV1FLRFhnZTQyQk55UVNnR1AxL1h5VjV0UktqajI1d3pUTGFLa1ZGYUsreXBsem9HRzlXcFFEb0hpRW5XemMxdE9kOTllNXloOUpNSjU1UlFKUFBzUW5IaFJNQXFwQ3FKenp1YjFXOU1ubWZ5N1g1Ky9wS09mVGkrZk9DKzNiVUViY2FORmxkeklLRzdMajUxVG83dnNzVUxwSjIzL0RwNmlpNjh0T21GeStiazUvVDdLc29QVFV4T2hOYmwxcTgzbng4bzhoZ3FINm9SSUZ3T1FRclFLSXdTRy82VHUvYTQreU05SDFEUTdxT3M2bnBrMTQzQ0FHQkFHOERLZmY0RW9oNGZQMFUzZGl5b05MR2pTWHkwOEM2UVliOE84bzhvb3N4UitNMFNJdU1PZDVCZ1JsWDVLOVJRMDVKSGhCc01DS0JaOUk3b2h5dVBvZGVYUkcybDB4TTdvL0Q4a2o2NG14Q2taeE5KS0l5Ty8xVFBXUTk0dFJmOGcvalRNVGlNVmxXVzhZRnFMUzcwL01CaWJ5R2JNUzdNcGp0dTl5aGVXNEJPTkZJME9CaDdZSzdEcXhnSnI1VngvdmlIbVB0bHo3RVBGUlloZ0ZydjJCYzY2eXBQc2ZyMnVJTU9GVEtkb2lwWTdtT2NoeE5INXQ1UnNDVFNGZkZtL3hVS0Y0cEd0ZWM2d2VhcmI2ZjExWFQzK3R0R3dQVTBscVVlMnpyNm5nSUdveHBncy9ZY3MvTEk5QjIzbFdWNGhWc2laNVM3aE9sa3ZkdVBiaU1uWmFia3E5RGZSRHBPSTNJTmJwSTg0WDE2MmNBcWVFK3ZzNnhTQTUzNUNtejgrb0Q2cDQzRlpiNllQNlFMZ1YyZmlKRnlSSi9QTURMTzRwd0tRZXloWk5UcHcrd0RnbnA5aWloN2x3SGNvdkNHZHFLRkVPOGtiR29SVmgyM3lGc0t2WjFDQ2hYcm0ydXdXUmFnY1BheStMN05OYWdPbERyR1pacE9aYWI5OVFiYndGM2FPdXRBNkVSK2hEcTg1QzJBOU5zV0hJT0U5RFc0UU50Z0pFOWF1eFdtcnJycUFKRUZVQ0RaOXA4ZDdxSlhOb2FtekFucXF4ZTFIaVBkR1RuRGxhVFJ2U1ZZbmhxTU9Sc21ZN2dHbmtQYUNkeS9iTjI5MlZQenFKaGpFL08vUUoxa3FyVjVwaENNWWgxeDYwYy9KQjFkbjdoKzlTck12eWZkWDd2bm0xTGN1OGIvTlFqOCtiOUZxcW5qQjdlNDF1VldYT1BsTFlwN1dUOFBtbnI5aTNMbEkrdVNuK09VdmczaHZFNGJTQ0E3VE4wSVl1ejhJTFFUQ0hpQjVxQlU5WjBueUxqQ21iZnUxTm15aEdvcE54ajBscmQ3NzBLdStjTEtTbkFNOGx5THhrNW83djlIMUxkTzlLM3RxdC9tM0J0MUlWOTZWSTQ5MnUxR3gyUkNiNEk2Z1lSVlVmZ1lDbFlVYW16K082dGMvR2NVamdSR21aTXQ5enlGc0gyeDJIZ1gvdllqNjBFRWxWdlVCcDZBPT0\x3d'
//};
//
//document.write('<scr'+'ipt type="text/javascript" s'+'rc="' + RecaptchaState.server + 'js/recaptcha.js"></scr'+'ipt>');

 </script>
<?php
/*
 * This is a PHP library that handles calling reCAPTCHA.
 *    - Documentation and latest version
 *          http://recaptcha.net/plugins/php/
 *    - Get a reCAPTCHA API Key
 *          https://www.google.com/recaptcha/admin/create
 *    - Discussion group
 *          http://groups.google.com/group/recaptcha
 *
 * Copyright (c) 2007 reCAPTCHA -- http://recaptcha.net
 * AUTHORS:
 *   Mike Crawford
 *   Ben Maurer
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * The reCAPTCHA server URL's
 */
define("RECAPTCHA_API_SERVER", "//www.google.com/recaptcha/api");
define("RECAPTCHA_API_SECURE_SERVER", "//www.google.com/recaptcha/api");
define("RECAPTCHA_VERIFY_SERVER", "www.google.com");

/**
 * Encodes the given data into a query string format
 * @param $data - array of string elements to be encoded
 * @return string - encoded request
 */
function _recaptcha_qsencode ($data) {
        $req = "";
        foreach ( $data as $key => $value )
                $req .= $key . '=' . urlencode( stripslashes($value) ) . '&';

        // Cut the last '&'
        $req=substr($req,0,strlen($req)-1);
        return $req;
}



/**
 * Submits an HTTP POST to a reCAPTCHA server
 * @param string $host
 * @param string $path
 * @param array $data
 * @param int port
 * @return array response
 */
function _recaptcha_http_post($host, $path, $data, $port = 80) {

        $req = _recaptcha_qsencode ($data);

        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $http_request .= "Content-Length: " . strlen($req) . "\r\n";
        $http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
        $http_request .= "\r\n";
        $http_request .= $req;

        $response = '';
        if( false == ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) ) ) {
                die ('Could not open socket');
        }

        fwrite($fs, $http_request);

        while ( !feof($fs) )
                $response .= fgets($fs, 1160); // One TCP-IP packet
        fclose($fs);
        $response = explode("\r\n\r\n", $response, 2);

        return $response;
}



/**
 * Gets the challenge HTML (javascript and non-javascript version).
 * This is called from the browser, and the resulting reCAPTCHA HTML widget
 * is embedded within the HTML form it was called from.
 * @param string $pubkey A public key for reCAPTCHA
 * @param string $error The error given by reCAPTCHA (optional, default is null)
 * @param boolean $use_ssl Should the request be made over ssl? (optional, default is false)

 * @return string - The HTML to be embedded in the user's form.
 */
function recaptcha_get_html ($pubkey, $error = null, $use_ssl = false)
{
	if ($pubkey == null || $pubkey == '') {
		die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
	}
	
	if ($use_ssl) {
                $server = RECAPTCHA_API_SECURE_SERVER;
        } else {
                $server = RECAPTCHA_API_SERVER;
        }

        $errorpart = "";
        if ($error) {
           $errorpart = "&amp;error=" . $error;
        }
        return '<script type="text/javascript" src="'. $server . '/challenge?k=' . $pubkey . $errorpart . '"></script>

	<noscript>
  		<iframe src="'. $server . '/noscript?k=' . $pubkey . $errorpart . '" height="300" width="500" frameborder="0"></iframe><br/>
  		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
  		<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
	</noscript>';
}




/**
 * A ReCaptchaResponse is returned from recaptcha_check_answer()
 */
class ReCaptchaResponse {
        var $is_valid;
        var $error;
}


/**
  * Calls an HTTP POST function to verify if the user's guess was correct
  * @param string $privkey
  * @param string $remoteip
  * @param string $challenge
  * @param string $response
  * @param array $extra_params an array of extra variables to post to the server
  * @return ReCaptchaResponse
  */
function recaptcha_check_answer ($privkey, $remoteip, $challenge, $response, $extra_params = array())
{
	if ($privkey == null || $privkey == '') {
		die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
	}

	if ($remoteip == null || $remoteip == '') {
		die ("For security reasons, you must pass the remote ip to reCAPTCHA");
	}

	
	
        //discard spam submissions
        if ($challenge == null || strlen($challenge) == 0 || $response == null || strlen($response) == 0) {
                $recaptcha_response = new ReCaptchaResponse();
                $recaptcha_response->is_valid = false;
                $recaptcha_response->error = 'incorrect-captcha-sol';
                return $recaptcha_response;
        }

        $response = _recaptcha_http_post (RECAPTCHA_VERIFY_SERVER, "/recaptcha/api/verify",
                                          array (
                                                 'privatekey' => $privkey,
                                                 'remoteip' => $remoteip,
                                                 'challenge' => $challenge,
                                                 'response' => $response
                                                 ) + $extra_params
                                          );

        $answers = explode ("\n", $response [1]);
        $recaptcha_response = new ReCaptchaResponse();

        if (trim ($answers [0]) == 'true') {
                $recaptcha_response->is_valid = true;
        }
        else {
                $recaptcha_response->is_valid = false;
                $recaptcha_response->error = $answers [1];
        }
        return $recaptcha_response;

}

/**
 * gets a URL where the user can sign up for reCAPTCHA. If your application
 * has a configuration page where you enter a key, you should provide a link
 * using this function.
 * @param string $domain The domain where the page is hosted
 * @param string $appname The name of your application
 */
function recaptcha_get_signup_url ($domain = null, $appname = null) {
	return "https://www.google.com/recaptcha/admin/create?" .  _recaptcha_qsencode (array ('domains' => $domain, 'app' => $appname));
}

function _recaptcha_aes_pad($val) {
	$block_size = 16;
	$numpad = $block_size - (strlen ($val) % $block_size);
	return str_pad($val, strlen ($val) + $numpad, chr($numpad));
}

/* Mailhide related code */

function _recaptcha_aes_encrypt($val,$ky) {
	if (! function_exists ("mcrypt_encrypt")) {
		die ("To use reCAPTCHA Mailhide, you need to have the mcrypt php module installed.");
	}
	$mode=MCRYPT_MODE_CBC;   
	$enc=MCRYPT_RIJNDAEL_128;
	$val=_recaptcha_aes_pad($val);
	return mcrypt_encrypt($enc, $ky, $val, $mode, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
}


function _recaptcha_mailhide_urlbase64 ($x) {
	return strtr(base64_encode ($x), '+/', '-_');
}

/* gets the reCAPTCHA Mailhide url for a given email, public key and private key */
function recaptcha_mailhide_url($pubkey, $privkey, $email) {
	if ($pubkey == '' || $pubkey == null || $privkey == "" || $privkey == null) {
		die ("To use reCAPTCHA Mailhide, you have to sign up for a public and private key, " .
		     "you can do so at <a href='http://www.google.com/recaptcha/mailhide/apikey'>http://www.google.com/recaptcha/mailhide/apikey</a>");
	}
	

	$ky = pack('H*', $privkey);
	$cryptmail = _recaptcha_aes_encrypt ($email, $ky);
	
	return "http://www.google.com/recaptcha/mailhide/d?k=" . $pubkey . "&c=" . _recaptcha_mailhide_urlbase64 ($cryptmail);
}

/**
 * gets the parts of the email to expose to the user.
 * eg, given johndoe@example,com return ["john", "example.com"].
 * the email is then displayed as john...@example.com
 */
function _recaptcha_mailhide_email_parts ($email) {
	$arr = preg_split("/@/", $email );

	if (strlen ($arr[0]) <= 4) {
		$arr[0] = substr ($arr[0], 0, 1);
	} else if (strlen ($arr[0]) <= 6) {
		$arr[0] = substr ($arr[0], 0, 3);
	} else {
		$arr[0] = substr ($arr[0], 0, 4);
	}
	return $arr;
}

/**
 * Gets html to display an email address given a public an private key.
 * to get a key, go to:
 *
 * http://www.google.com/recaptcha/mailhide/apikey
 */
function recaptcha_mailhide_html($pubkey, $privkey, $email) {
	$emailparts = _recaptcha_mailhide_email_parts ($email);
	$url = recaptcha_mailhide_url ($pubkey, $privkey, $email);
	
	return htmlentities($emailparts[0]) . "<a href='" . htmlentities ($url) .
		"' onclick=\"window.open('" . htmlentities ($url) . "', '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=300'); return false;\" title=\"Reveal this e-mail address\">...</a>@" . htmlentities ($emailparts [1]);

}


?>
