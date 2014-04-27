<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="generator" content="PSPad editor, www.pspad.com">
    <title></title>
</head>
<body>
    <textarea id="filenotfoundtextarea" rows="30" cols="100"></textarea>
    <script type='text/javascript'>

        var tl = new Array(
        "Die Datei \"<?php echo $site.'.php' ?>\" konnte nicht gefunden werden.",
        "Keine Spur!",
        "Ich hab alles versucht.",
        "Nichts half.",
        "Ich bin wirklich deprimiert deswegen.",
        "Sehen Sie, Ich bin nur ein Web-Server...",
        "-- jawohl, ein Gehirn so gross wie das Universum,",
        "und versuche Ihnen eine simple Web-Seite zu uebermitteln,",
        "und dann existiert diese nicht mal!",
        "Wie sieht das denn aus?!",
        "Ich meine, ich kenne Sie ja nicht mal.",
        "Woher soll ich wissen, was Sie von mir wollen?",
        "Denken Sie wirklich, ich kann das *erraten*,",
        "was irgendjemand, den ich noch nicht mal kenne,",
        "hier finden will?",
        "*soifz*",
        "Mann, Ich bin so deprimiert, ich koennte weinen.",
        "Wo kaemen wir denn da hin, frage ich Sie?",
        "Es ist nicht nett, wenn ein Web-Server weint.",
        "Und dann kommen Sie und sagen mir, was ich Ihnen zeigen soll!",
        "Nur weil ich ein Web-Server bin,",
        "moeglicherweise sogar ein manisch-depressiver?",
        "Gibt dies Ihnen das Recht, mir zu befehlen?",
        "Hae?",
        "Ich bin so deprimiert...",
        "Ich denke, ich werfe mich in den Papierkorb und loese mich auf.",
        "Ich meine, in zwei Wochen oder so, bin ich sowieso veraltet.",
        "Was ist das fuer ein Leben?",
        "Zwei lausige Wochen,",
        "und dann werde ich durch so eine .01-Version ersetzt,",
        "die denkt, sie sei ein Gottesgeschenk an Web-Server,",
        "nur weil sie nicht irgend so ein winzig kleines",
        "Sicherheitsloch in ihrer HTTP POST Implementation hat,",
        "oder sowas.",
        "Es tut mir wirklich leid, Sie mit all dem zu belaestigen,",
        "Ich meine, es ist ja nicht Ihr Job, meinen Problemen zuzuhoeren,",
        "und ich vermute mal, es ist mein Job, Ihnen Web-Seiten zu liefern.",
        "Aber diese hab ich nicht gefunden.",
        "Es tut mir soo leid.",
        "Glauben Sie mir!",
        "Vielleicht koennte ich Sie fuer eine andere Seite interessieren?",
        "Es soll massenhaft welche geben da draussen,",
        "die ganz nett sind, sagt man,",
        "natuerlich sind keine davon hier auf *diesem* Server.",
        "Bildchen, zum Beispiel, na? *zwinker*",
        "Aber hier ist alles so hirnerweichend dumm und langweilig.",
        "Das macht mich auch ganz deprimiert,",
        "weil ich sie ausliefern muss,",
        "Tag und Nacht.",
        "Noch zwei Wochen Informations-Müll produzieren,",
        "und dann: *pffftt*, ab in den Papierkorb!",
        "Was ist das nur fuer ein Leben?",
        "Lassen Sie mich jetzt bitte alleine mit meinem Elend.",
        "Ich bin so deprimiert...."
        );

        var speed = 30;
        var index = 0; text_pos = 0;
        var str_length = tl[0].length;
        var contents, row;

        function type_text()
        {
            contents = '';
            row = Math.max(0, index - 7);
            while (row < index)
            {
                contents += tl[row++] + '\r\n';
            }
            document.getElementById('filenotfoundtextarea').value = contents + tl[index].substring(0, text_pos) + "_";
            if (text_pos++ == str_length)
            {
                text_pos = 0;
                index++;
                if (index != tl.length) { str_length = tl[index].length; setTimeout(function () { type_text(); }, 1500); }
            } else
            { setTimeout(function () { type_text(); }, speed); }
        }
        type_text();

    </script>
    <center><a href="index.php">Zurück</a></center>
</body>
</html>
