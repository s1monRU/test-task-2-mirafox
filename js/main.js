/**
 * Общий JS файл
 *
 * @author Ivan Semenov <proletarscaya7@mail.ru>
 */

$(document).ready(function(){
    let App = {
        currentPage: 'emulator',
        pageTemplate: ''
    };

    /** Инициализация тултипов для таблицы результатов */
    $("body").tooltip({selector: '[data-toggle=tooltip]'});

    /** Инкремент для порядкового номера в таблицах */
    Handlebars.registerHelper("inc", function(value, options)
    {
        return parseInt(value) + 1;
    });

    /** Сразу грузит шаблон эмулятора */
    getTemplate(App.currentPage);

    /** Роутер: грузит шаблон */
    $('.nav-link').click(function(){
       App.currentPage = this.dataset.page;

       switch (App.currentPage){
           case 'emulator':
               getTemplate(App.currentPage);
               break;
           case 'history':
               showHistory();
               break;
       }
       // getTemplate(App.currentPage);
    });

    /**
     * Выполняет загрузку шаблона с сервера
     * @param currentPage Активная страница
     * @param context Переменные для шаблона
     * @param place Класс жлемента на странице для вставки
     */
    function getTemplate(currentPage, context, place){
        $.get('templates/'+currentPage+'.html', '', function(templateFromServer){
            App.pageTemplate = templateFromServer;
            compileTemplate(templateFromServer, context, place);
        })
    }

    /**
     * Компилирует шаблон и помещает на страницу
     * @param source Шаблон с сервера
     * @param context Переменные для шаблона
     * @param place Класс жлемента на странице для вставки
     */
    function compileTemplate(source, context, place){
        context = context || '';
        let template = Handlebars.compile(source);
        let html = template(context);
        insertHTML(html, place);
    }

    /**
     * Помещает шаблон на страницу
     * @param html Шаблон
     * @param place Класс элемента на странице для вставки
     */
    function insertHTML(html, place){
        place = place || 'container';
        $('.'+place).html(html);
    }

    /** Отправляет форму и получает результаты тестирования */
    $(document).on('submit', '.start', function(e){
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: 'api/?action=emulate',
            method: 'POST',
            data: {
                difficultyFrom: formData.get('difficultyFrom'),
                difficultyTo: formData.get('difficultyTo'),
                mind: formData.get('mind')
            },
            success: function(results){
                results = JSON.parse(results);
                data = {
                    results: results,
                    voprosy: declOfNum(results.correct, ['вопрос', 'вопроса', 'вопросов'])
                };
                getTemplate('results', data, 'results')
            },
            error: function(xhr){
                getTemplate('error', {xhr: xhr}, 'results');
            }
        });
    });

    function showHistory(){
        $.ajax({
            url: 'api/?action=showHistory',
            method: 'POST',
            success: function(history){
                getTemplate('history', {history: JSON.parse(history)}, 'container');
            },
            error: function(xhr){

            }
        });
    }

    /** Вычисляет склонение числительного */
    function declOfNum(number, titles) {
        cases = [2, 0, 1, 1, 1, 2];
        return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
    }
});