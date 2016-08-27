peque.news = function ()
{
    'use strict';

    var initArticle = function ()
    {
        $("textarea[name=text]").sceditor({
            plugins: "bbcode",
            readOnly: true,
            toolbar: '',
            emoticonsEnabled: false,
            width: '90%',
            style: "/css/lib/jquery.sceditor.default.min.css"
        });
    };

    var initArticleForm = function ()
    {
        $("textarea[name=text]").sceditor({
            plugins: "bbcode",
            toolbar: 'bold,italic,underline,strike|left,center,right,justify|size,color,removeformat,pastetext|bulletlist,orderedlist|quote,image,youtube,link,unlink|maximize,source',
            emoticonsEnabled: false,
            width: '90%',
            style: "/css/lib/jquery.sceditor.default.min.css"
        });

        $('#article-creation-form').on("submit", function (e) {
            e.preventDefault();

            var title = $('[name=title]').val(),
                category = $('[name=category]').val(),
                text = $('[name=text]').val();

            if (title.length < 3 || text.length < 3 ) {
                return false;
            }

            peque.api("article/create", {title: title, text: text, category: category}, function (data) {
                if (data.error > 0) {
                    return false;
                }

                peque.navigation.redirect("/news/article/" + data.result);
            });
        });
    };

    var initCreationForm = function ()
    {
        $('#newspaper-creation-form').on("submit", function (e) {
            e.preventDefault();

            var name = $('[name=name]').val(),
                description = $('[name=description]').val();

            if (name.length < 3 || description.length < 3 ) {
                return false;
            }

            peque.api("newspaper/create", {name: name, description: description}, function (data) {
                if (data.error > 0) {
                    return false;
                }

                peque.navigation.redirect("/newspaper/" + data.result);
            });
        });
    };

    return {
        initArticle: initArticle,
        initCreationForm: initCreationForm,
        initArticleForm: initArticleForm
    };
}();