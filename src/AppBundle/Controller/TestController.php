<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Service\Parser_site_V2;

use jamesiarmes\PhpEws\Type\MessageType;
use jamesiarmes\PhpEws\Type\BodyType;

class TestController extends Controller
{
    /**
     *@Route("/test/", name="test")
     */
    public function index(Parser_site_V2 $parser)
    {
    	$testBody[0] = '<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http:// ▶
        <head>\r\n
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">\r\n
        <meta name="Generator" content="Microsoft Word 15 (filtered medium)">\r\n
        <!--[if !mso]><style>v\:* {behavior:url(#default#VML);}\r\n
        o\:* {behavior:url(#default#VML);}\r\n
        w\:* {behavior:url(#default#VML);}\r\n
        .shape {behavior:url(#default#VML);}\r\n
        </style><![endif]--><style><!--\r\n
        /* Font Definitions */\r\n
        @font-face\r\n
        \t{font-family:"Cambria Math";\r\n
        \tpanose-1:2 4 5 3 5 4 6 3 2 4;}\r\n
        @font-face\r\n
        \t{font-family:Calibri;\r\n
        \tpanose-1:2 15 5 2 2 2 4 3 2 4;}\r\n
        /* Style Definitions */\r\n
        p.MsoNormal, li.MsoNormal, div.MsoNormal\r\n
        \t{margin:0cm;\r\n
        \tmargin-bottom:.0001pt;\r\n
        \tfont-size:11.0pt;\r\n
        \tfont-family:"Calibri",sans-serif;\r\n
        \tmso-fareast-language:EN-US;}\r\n
        a:link, span.MsoHyperlink\r\n
        \t{mso-style-priority:99;\r\n
        \tcolor:#0563C1;\r\n
        \ttext-decoration:underline;}\r\n
        a:visited, span.MsoHyperlinkFollowed\r\n
        \t{mso-style-priority:99;\r\n
        \tcolor:#954F72;\r\n
        \ttext-decoration:underline;}\r\n
        p.msonormal0, li.msonormal0, div.msonormal0\r\n
        \t{mso-style-name:msonormal;\r\n
        \tmso-margin-top-alt:auto;\r\n
        \tmargin-right:0cm;\r\n
        \tmso-margin-bottom-alt:auto;\r\n
        \tmargin-left:0cm;\r\n
        \tfont-size:11.0pt;\r\n
        \tfont-family:"Calibri",sans-serif;}\r\n
        span.EmailStyle18\r\n
        \t{mso-style-type:personal;\r\n
        \tfont-family:"Calibri",sans-serif;\r\n
        \tcolor:windowtext;}\r\n
        span.EmailStyle19\r\n
        \t{mso-style-type:personal;\r\n
        \tfont-family:"Calibri",sans-serif;\r\n
        \tcolor:windowtext;}\r\n
        span.EmailStyle20\r\n
        \t{mso-style-type:personal;\r\n
        \tfont-family:"Calibri",sans-serif;\r\n
        \tcolor:windowtext;}\r\n
        span.EmailStyle21\r\n
        \t{mso-style-type:personal;\r\n
        \tfont-family:"Calibri",sans-serif;\r\n
        \tcolor:windowtext;}\r\n
        span.EmailStyle23\r\n
        \t{mso-style-type:personal-reply;\r\n
        \tfont-family:"Calibri",sans-serif;\r\n
        \tcolor:windowtext;}\r\n
        .MsoChpDefault\r\n
        \t{mso-style-type:export-only;\r\n
        \tfont-size:10.0pt;}\r\n
        @page WordSection1\r\n
        \t{size:612.0pt 792.0pt;\r\n
        \tmargin:70.85pt 70.85pt 70.85pt 70.85pt;}\r\n
        div.WordSection1\r\n
        \t{page:WordSection1;}\r\n
        --></style><!--[if gte mso 9]><xml>\r\n
        <o:shapedefaults v:ext="edit" spidmax="1026" />\r\n
        </xml><![endif]--><!--[if gte mso 9]><xml>\r\n
        <o:shapelayout v:ext="edit">\r\n
        <o:idmap v:ext="edit" data="1" />\r\n
        </o:shapelayout></xml><![endif]-->\r\n
        </head>\r\n
        <body lang="FR" link="#0563C1" vlink="#954F72">\r\n
        <div class="WordSection1">\r\n
        <p class="MsoNormal">Voici le certif<o:p></o:p></p>\r\n
        <p class="MsoNormal"><o:p>&nbsp;</o:p></p>\r\n
        <p class="MsoNormal"><o:p>&nbsp;</o:p></p>\r\n
        <div>\r\n
        <div style="border:none;border-top:solid #E1E1E1 1.0pt;padding:3.0pt 0cm 0cm 0cm">\r\n
        <p class="MsoNormal"><b><span style="mso-fareast-language:FR">De&nbsp;:</span></b><span style="mso-fareast-language:FR"> Philippe QUEBERT &lt;p.quebert@as-solut ▶
        <br>\r\n
        <b>Envoyé&nbsp;:</b> jeudi 5 juillet 2018 17:18<br>\r\n
        <b>À&nbsp;:</b> Luc CROS &lt;Luc.CROS@nextmedia.fr&gt;<br>\r\n
        <b>Objet&nbsp;:</b> RE: connexion en webservice a exchange pour envoi de mail<br>\r\n
        <b>Importance&nbsp;:</b> Haute<o:p></o:p></span></p>\r\n
        </div>\r\n
        </div>\r\n
        <p class="MsoNormal"><o:p>&nbsp;</o:p></p>\r\n
        <p class="MsoNormal">Tu trouveras le certificat en pièce jointe, il faut juste renommer l’extension en «&nbsp;cer&nbsp;»<o:p></o:p></p>\r\n
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        <div>\r\n
        <p class="MsoNormal"><a name="Philippe_QUEBERT2"><span style="mso-fareast-language:FR"><img width="250" height="149" style="width:2.6041in;height:1.552in" id="_ ▶
        </div>\r\n
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        <div>\r\n
        <div style="border:none;border-top:solid #E1E1E1 1.0pt;padding:3.0pt 0cm 0cm 0cm">\r\n
        <p class="MsoNormal"><b><span style="mso-fareast-language:FR">De&nbsp;:</span></b><span style="mso-fareast-language:FR"> Luc CROS [<a href="mailto:Luc.CROS@next ▶
        <br>\r\n
        <b>Envoyé&nbsp;:</b> jeudi 5 juillet 2018 12:12<br>\r\n
        <b>À&nbsp;:</b> Philippe QUEBERT &lt;<a href="mailto:p.quebert@as-solution.fr">p.quebert@as-solution.fr</a>&gt;<br>\r\n
        <b>Objet&nbsp;:</b> RE: connexion en webservice a exchange pour envoi de mail</span><o:p></o:p></p>\r\n
        </div>\r\n
        </div>\r\n
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        <p class="MsoNormal">Tu te souviens du liens sur le serveur exchange<o:p></o:p></p>\r\n
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        <p class="MsoNormal"><a href="https://antiphishing.vadesecure.com/2/cC5xdWViZXJ0QGFzLXNvbHV0aW9uLmZyfFZSQzA0NjY2Mg%3D%3D/vsrvexc1/">https://vsrvexc1/</a>....<o: ▶
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        <p class="MsoNormal">Merci d’avance<o:p></o:p></p>\r\n
        <div>\r\n
        <table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="658" style="width:493.3pt;margin-left:16.2pt;border-collapse:collapse">\r\n
        <tbody>\r\n
        <tr style="height:59.1pt">\r\n
        <td width="213" style="width:159.85pt;padding:0cm 5.4pt 0cm 5.4pt;height:59.1pt">\r\n
        <p class="MsoNormal" align="right" style="text-align:right"><a href="https://antiphishing.vadesecure.com/2/cC5xdWViZXJ0QGFzLXNvbHV0aW9uLmZyfFZSQzA0NjY2Mg%3D%3D/ ▶
        <p class="MsoNormal"><span style="font-size:10.0pt;mso-fareast-language:FR">&nbsp;</span><o:p></o:p></p>\r\n
        <p class="MsoNormal" align="right" style="text-align:right"><span style="font-size:10.0pt;mso-fareast-language:FR"><a href="https://antiphishing.vadesecure.com/ ▶
        <p class="MsoNormal" align="right" style="text-align:right"><span style="font-size:10.0pt;mso-fareast-language:FR">&nbsp;</span><o:p></o:p></p>\r\n
        </td>\r\n
        <td width="188" valign="top" style="width:141.3pt;padding:0cm 5.4pt 0cm 5.4pt;height:59.1pt">\r\n
        <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-size:10.0pt;mso-fareast-language:FR">&nbsp;</span></b><o:p></o:p></p>\r\n
        <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-size:10.0pt;mso-fareast-language:FR">&nbsp;</span></b><o:p></o:p></p>\r\n
        <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-size:10.0pt;mso-fareast-language:FR">Luc Cros</span></b><o:p></o:p></p>\r\n
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">Business Manager</span><o:p>< ▶
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">06 81 88 45 64</span><o:p></o ▶
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">51 rue des Courtiaux</span><o ▶
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">La Pardieu</span><o:p></o:p>< ▶
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">63000 Clermont-Ferrand</span> ▶
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">04 73 14 32 82</span><o:p></o ▶
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">&nbsp;</span><o:p></o:p></p>\ ▶
        <p class="MsoNormal"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">&nbsp;</span><o:p></o:p></p>\r\n
        <p class="MsoNormal"><span style="font-size:10.0pt;mso-fareast-language:FR">&nbsp;</span><o:p></o:p></p>\r\n
        </td>\r\n
        <td width="256" style="width:192.15pt;padding:0cm 5.4pt 0cm 5.4pt;height:59.1pt">\r\n
        <p class="MsoNormal"><a href="https://antiphishing.vadesecure.com/1/cC5xdWViZXJ0QGFzLXNvbHV0aW9uLmZyfFZSQzA0NjY2Mg%3D%3D/www.quizzbox.com/"><span style="font-si ▶
         et <a href="https://antiphishing.vadesecure.com/2/cC5xdWViZXJ0QGFzLXNvbHV0aW9uLmZyfFZSQzA0NjY2Mg%3D%3D/info.quizzweb.fr/">\r\n
        <span style="color:blue">https://info.quizzweb.fr/</span></a></span><o:p></o:p></p>\r\n
        </td>\r\n
        </tr>\r\n
        </tbody>\r\n
        </table>\r\n
        <p class="MsoNormal"><span style="mso-fareast-language:FR"><img border="0" width="606" height="114" style="width:6.3125in;height:1.1875in" id="_x0000_i1028" src ▶
        </div>\r\n
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        <div>\r\n
        <div style="border:none;border-top:solid #E1E1E1 1.0pt;padding:3.0pt 0cm 0cm 0cm">\r\n
        <p class="MsoNormal"><b><span style="mso-fareast-language:FR">De&nbsp;:</span></b><span style="mso-fareast-language:FR"> Philippe QUEBERT &lt;<a href="mailto:p. ▶
        <br>\r\n
        <b>Envoyé&nbsp;:</b> mercredi 4 juillet 2018 18:37<br>\r\n
        <b>À&nbsp;:</b> Luc CROS &lt;<a href="mailto:Luc.CROS@nextmedia.fr">Luc.CROS@nextmedia.fr</a>&gt;<br>\r\n
        <b>Objet&nbsp;:</b> RE: connexion en webservice a exchange pour envoi de mail</span><o:p></o:p></p>\r\n
        </div>\r\n
        </div>\r\n
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        <p class="MsoNormal">Il faut importer le certificat de la CA nextmedia pour toutes connexions au serveur exchange<o:p></o:p></p>\r\n
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        <div>\r\n
        <p class="MsoNormal"><a name="Philippe_QUEBERT"><span style="mso-fareast-language:FR"><img border="0" width="250" height="149" style="width:2.6041in;height:1.55 ▶
        </div>\r\n
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        <div>\r\n
        <div style="border:none;border-top:solid #E1E1E1 1.0pt;padding:3.0pt 0cm 0cm 0cm">\r\n
        <p class="MsoNormal"><b><span style="mso-fareast-language:FR">De&nbsp;:</span></b><span style="mso-fareast-language:FR"> Luc CROS [<a href="mailto:Luc.CROS@next ▶
        <br>\r\n
        <b>Envoyé&nbsp;:</b> mercredi 4 juillet 2018 16:56<br>\r\n
        <b>À&nbsp;:</b> Philippe QUEBERT &lt;<a href="mailto:p.quebert@as-solution.fr">p.quebert@as-solution.fr</a>&gt;<br>\r\n
        <b>Objet&nbsp;:</b> connexion en webservice a exchange pour envoi de mail</span><o:p></o:p></p>\r\n
        </div>\r\n
        </div>\r\n
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        <p class="MsoNormal">On a besoin d’un certificat pour autoriser la connexion a priori.<o:p></o:p></p>\r\n
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        <p class="MsoNormal">Pourrais tu m’éclairer sur le sujet&nbsp;?<o:p></o:p></p>\r\n
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        <p class="MsoNormal">Merci<o:p></o:p></p>\r\n
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        <table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="658" style="width:493.3pt;margin-left:16.2pt;border-collapse:collapse">\r\n
        <tbody>\r\n
        <tr style="height:59.1pt">\r\n
        <td width="213" style="width:159.85pt;padding:0cm 5.4pt 0cm 5.4pt;height:59.1pt">\r\n
        <p class="MsoNormal" align="right" style="text-align:right"><a href="https://antiphishing.vadesecure.com/2/cC5xdWViZXJ0QGFzLXNvbHV0aW9uLmZyfFZSQzA0NjY2Mg%3D%3D/ ▶
        <p class="MsoNormal"><span style="font-size:10.0pt;mso-fareast-language:FR">&nbsp;</span><o:p></o:p></p>\r\n
        <p class="MsoNormal" align="right" style="text-align:right"><span style="font-size:10.0pt;mso-fareast-language:FR"><a href="https://antiphishing.vadesecure.com/ ▶
        <p class="MsoNormal" align="right" style="text-align:right"><span style="font-size:10.0pt;mso-fareast-language:FR">&nbsp;</span><o:p></o:p></p>\r\n
        </td>\r\n
        <td width="188" valign="top" style="width:141.3pt;padding:0cm 5.4pt 0cm 5.4pt;height:59.1pt">\r\n
        <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-size:10.0pt;mso-fareast-language:FR">&nbsp;</span></b><o:p></o:p></p>\r\n
        <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-size:10.0pt;mso-fareast-language:FR">&nbsp;</span></b><o:p></o:p></p>\r\n
        <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-size:10.0pt;mso-fareast-language:FR">Luc Cros</span></b><o:p></o:p></p>\r\n
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">Business Manager</span><o:p>< ▶
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">06 81 88 45 64</span><o:p></o ▶
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">51 rue des Courtiaux</span><o ▶
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">La Pardieu</span><o:p></o:p>< ▶
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">63000 Clermont-Ferrand</span> ▶
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">04 73 14 32 82</span><o:p></o ▶
        <p class="MsoNormal" align="center" style="text-align:center"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">&nbsp;</span><o:p></o:p></p>\ ▶
        <p class="MsoNormal"><span style="font-size:10.0pt;color:#7F7F7F;mso-fareast-language:FR">&nbsp;</span><o:p></o:p></p>\r\n
        <p class="MsoNormal"><span style="font-size:10.0pt;mso-fareast-language:FR">&nbsp;</span><o:p></o:p></p>\r\n
        </td>\r\n
        <td width="256" style="width:192.15pt;padding:0cm 5.4pt 0cm 5.4pt;height:59.1pt">\r\n
        <p class="MsoNormal"><a href="https://antiphishing.vadesecure.com/2/cC5xdWViZXJ0QGFzLXNvbHV0aW9uLmZyfFZSQzA0NjY2Mg%3D%3D/antiphishing.vadesecure.com/1/cC5xdWViZ ▶
         et <a href="https://antiphishing.vadesecure.com/2/cC5xdWViZXJ0QGFzLXNvbHV0aW9uLmZyfFZSQzA0NjY2Mg%3D%3D/antiphishing.vadesecure.com/2/cC5xdWViZXJ0QGFzLXNvbHV0aW ▶
        <span style="color:blue">https://info.quizzweb.fr/</span></a></span><o:p></o:p></p>\r\n
        </td>\r\n
        </tr>\r\n
        </tbody>\r\n
        </table>\r\n
        <p class="MsoNormal"><span style="mso-fareast-language:FR"><img border="0" width="606" height="114" style="width:6.3125in;height:1.1875in" id="Image_x0020_3" sr ▶
        <p class="MsoNormal">&nbsp;<o:p></o:p></p>\r\n
        </div>\r\n
        </body>\r\n
        </html>\r\n';

        $testBody[1] = "<html>
						<head>
							<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>

							<!--	En Tete possibles 	-->
							<meta name='type_demande' content='devis'><!-- [DEVIS contact] -->
							<meta name='from' content='fr'><!-- [al en fr] -->
							<!--...-->
							<title>Gendarmerie - Demande devis site web</title>
							<style>* {margin:0px;padding:0px;} body {font-family:Calibri,Arial;font-size:1.1em;}dl {margin-left:10px;margin-top:10px;} dt{color:#CC3300;} dd {margin-left:10px;}</style>
						</head>
						<body>
							Type de devis : <span data-type='devis_type'>Achat</span><br>
							Societe : <span data-type='societe'>Gendarmerie</span><br>
							Civilite : <span data-type='civilite'>Monsieur</span><br>
							Nom : <span data-type='nom'>VIGNEUX</span><br>
							Adresse : <span data-type='rue'>BP 89 Caserne Bruat</span><br>
							<!-- Complément : <span data-type='complement'>BAT C, ESC 2, ETAGE 5</span></br> -->
							Code Postal : <span data-type='cp'>98713</span><br>
							Ville : <span data-type='ville'>PAPEETE</span><br>
							Telephone : <span data-type='tel'>00 68 97 23 87 1</span><br>
							Email : <span data-type='email'>sylvain.vigneux@gendarmerie.interieur.gouv.fr</span><br>
							Type de systeme : <span data-type='system_type'>Education</span><br>
							Nombre de boitiers : <span data-type='nb_controller'>30</span><br>
							Provenance : <span data-type='provenance'>Google</span><br>
							Infos en plus : <span data-type='commentaire'>Bonjour,nous effectuons régulièrement des opérations de sensibilisation à la sécurité routière au sein des établissements scolaires et en entreprises. Nous souhaiterions rendre ses interventions plus interactives. Nous envisageons de créer des présentations avec open office impress type code de la route c'est à dire des situations matérialisées par des photos et des questions auxquelles les participants pourraient répondre par boitier (4 à 5 choix de réponse possibles). Mais, il est impératif que l'animateur dispose d'un tableau de bord qui lui indique a la fin de chaque question le nombre de réponses validées et les résultats obtenus de manière à orienter son intervention. Les boitiers QuizzBox semblent répondre à nos attentes. Si tel est le cas, pouvez-vous nous orienter sur le choix du matériel ? Existe-t-il des boitiers rechargeables pour éviter de changer régulièrement les piles ? Livrez-vous en Polynésie Française ? Dans ce cas, merci de me faire parvenir un devis. Cordialement. Sylvain VIGNEUX</span><br>
						</body>
					</html>";

		$testBody[2] = "<html>
						    <head>
						        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>

						        <!--    En Tete possibles   -->
						        <meta name='type_demande' content='contact'><!-- [devis CONTACT] -->
						        <meta name='from' content='en'><!-- [al en fr] -->
						        <!--...-->
						        <title>Sorbonne Université - Demande de contact site internet</title>
						    </head>
						    <body style='font-family:Calibri;font-size:1.1em;'>
						        Societe : <span data-type='societe'>Sorbonne Université</span><br>
						        Nom : <span data-type='nom'>BITTNER</span><br>
						        <!-- Ville : <span data-type='Ville'>PARIS</span><br> -->
						        Telephone : <span data-type='tel'>01 44 27 46 92</span><br>
						        <!-- Email : <span data-type='Email'>bittner.jeanpascal@sorb.univ.fr</span></br> -->
						        Infos en plus : <span data-type='commentaire'>Bonjour j'ai fait une demande de devis, celle-ci est traitée par Pierre-Antoine GACZYNSKI, mais il a oublié de me joindre le document lors de son dernier email, et il actuellement il ne répond plus à mes mails. quelqu'un d'autre pourrait il me fournir ce devis ? je souhaiterais un devis pour : 20 boitiers étudiants et 2 boitiers enseignants, et une journée de formation à Paris, campus de l'Université Pierre et Marie Curie, metro Jussieu. Merci par avance pour votre retour, cordialement.</span><br>
						    </body>
						</html>";

    	$mail = new MessageType();

    	$mail->Body = new BodyType();
    	$mail->Body->BodyType = "HTML";
        $mail->Body->_ = $testBody[rand(0,2)];

		$mail->DateTimeReceived = "2018-07-25T02:06:24Z";

		if($parser->preFilter($mail)){
			$em = $this->getDoctrine()->getManager();
			$affaire = $parser->parser($mail);
			//$em->persist($affaire);
			$rsp = $affaire;
		}else{
			$rsp = "ERROR";
		}
		//$em->flush();

        return $this->render('Default/test.html.twig', ['rsp' => $rsp]);
    }
}