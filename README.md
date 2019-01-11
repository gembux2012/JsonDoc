# Создание и редактирование JSON

<!-- BADGES/ -->
Прриложение создано на основе фрэймворка 
[T4](https://github.com/pr-of-it/t4-app-mini) 

- [Demo](http://194437.simplecloud.ru./) пароль для входа root
- [Приступая к разработке](#Приступая-к-разработке)
  - [Config](#Config)
  - [Создание таблиц](#Создание-таблиц)
  - [Авторизация](#Авторизация)
  - [Доступ](#Доступ)
    - [Контроллер](#Контроллер)
      - [Методы GetList(), List()](#методы-GetList(),-List())
  
  - [Фротэнд](#Фронтэнд)     
      

## Быстрый старт
1. [Install composer](https://getcomposer.org)
2. [Install docker](https://docs.docker.com/install/)
3. [Install docker-compose](https://docs.docker.com/compose/install/)
4. Поскольку данный проект не нужен на packagist.org то:
  - git clone https://github.com/gembux2012/jsondoc
  - cd jsondoc
  - composer install  
  - composer run-script docker:build
   
5. Go to [http://jsondoc/](http://jsondoc/)

## Приступая к разработке
 Не являсь профессиональным веб-разработчиком, никак не мог понять в каком случае
 может понадобиться пользователю на сайте создавать и редактировать
 JSON.  
 Так же много времени заняло освоение Docker-a, поскольку 
 я им не разу не пользовался.
 Описывать  [docker](https://github.com/gembux2012/jsondoc/tree/master/docker) файлы
 и  [docker-compose](https://github.com/gembux2012/jsondoc/blob/master/docker-compose.yml)
 не вижу смысла, все стандартно. 
 
   Собственно само задание, отдать  данные на страницу в формате json
   не вызывает никаких затруднений. Читаем json прямо из файла задания и
   заполняем его даннымми из таблицы. Только непонятно, что потом с этими 
   json_ами делать. Отрисовывать страницу читая из них данные,так это 
   нечитабельный код на js(может это и не так, но я не нашел вменяемого способа
   создание html на js).
   Поэтому на чистом js работает пагинатор и  аутентификация.  
   Весь остальной интерфейс: подготоаливается кусок страницы на HTML 
   и подгружается  с помощью .load();
Так же не смог выяснить зачем нужен метод PATCH.
Без него все прекрасно.

   
    
Ну и собственно приступаем: 
 
 Для начала в соответствии с экоситемой фрэймворка создаем в 
 [/protected](https://github.com/gembux2012/jsondoc/tree/master/protected) :  
  -[Commands](https://github.com/gembux2012/jsondoc/tree/master/protected/Commands) - работа с командной строки  
  -[Components/Auth](https://github.com/gembux2012/jsondoc/tree/master/protected/Components) - авторизация  
  -[Controllers](https://github.com/gembux2012/jsondoc/tree/master/protected/Controllers) - дефолтный контроллер  
  -[Models](https://github.com/gembux2012/jsondoc/tree/master/protected/Models) - модели таблиц  
  -[Templates/Index](https://github.com/gembux2012/jsondoc/tree/master/protected/Templates/Index) - вьюеры экшенов контроллера  
 
## Config
Устнавливаем значения [config.php](https://github.com/gembux2012/jsondoc/blob/master/protected/config.php) фрэймворка.  
[ 'db' =>](https://github.com/gembux2012/jsondoc/blob/master/protected/config.php) - подключение
к mysql;  
['auth' =>](https://github.com/gembux2012/jsondoc/blob/master/protected/config.php) - длительность сесии;   
 ['extensions' =>](https://github.com/gembux2012/jsondoc/blob/master/protected/config.php) - подключаем расширения 
 bootstrap и jquery;  
['errors' =>](https://github.com/gembux2012/jsondoc/blob/master/protected/config.php) - вызов экшенов на ошибки 403 404 



## Создание таблиц
 
 В фрэймворке существует механизм миграций, но в данном случае я его не использую.
 Создадим в /Commands класс [CreateTables унаследованный от Command](https://github.com/gembux2012/jsondoc/blob/master/protected/Commands/CreateTables.php#L10)
 c экшеном Init().  
 Описываем создание 3 таблиц на SQL:  
  -users - пользователи  
  -documents - документы json созданные пользователями  
  -sessions - для хранения сессий  
  Назначение полей понятно из названий.    
  Используем ORM фрэймворка. Он прост по сравнению с Doctrine 
  и позволяет выполнять любые sql запросы.  
  Метод [execute()](https://github.com/gembux2012/jsondoc/blob/master/protected/Commands/CreateTables.php#L37)- создает таблицы.  
  Создаем [пользователя](https://github.com/gembux2012/jsondoc/blob/master/protected/Commands/CreateTables.php#L41).  
  Командой php /protected/t4.php CreateTables/Init будут созданы таблицы и пользователь
  root.
  Для ОРМ описываем модели таблиц:
  - [Документы](https://github.com/gembux2012/jsondoc/blob/master/protected/Models/Document.php)
  - [Пользователи](https://github.com/gembux2012/jsondoc/blob/master/protected/Models/User.php)
  - [Сессии](https://github.com/gembux2012/jsondoc/blob/master/protected/Models/UserSession.php)
  
## Авторизация

Создаем класс [/protected/Components/Auth/Identity](https://github.com/gembux2012/jsondoc/blob/master/protected/Components/Auth/Identity.php#L12) наследник
от \T4\Auth\Identity
перегружаем методы нужные нам для авторизации:
  
 -[Login()](https://github.com/gembux2012/jsondoc/blob/master/protected/Components/Auth/Identity.php#L60)    
[Читаем из конфига срок действия куки](https://github.com/gembux2012/jsondoc/blob/master/protected/Components/Auth/Identity.php#L82)  
[получаем токен](https://github.com/gembux2012/jsondoc/blob/master/protected/Components/Auth/Identity.php#L83)  
[Выставляем юзеру кукку с токеном и сроком действия](https://github.com/gembux2012/jsondoc/blob/master/protected/Components/Auth/Identity.php#L82)  
[записываем в таблицу сесий токен, имя юзера и браузер юзера](https://github.com/gembux2012/jsondoc/blob/master/protected/Components/Auth/Identity.php#L70)  

-[getUser()]()  
Проверяем куку, если есть - читаем из куки токен. Ищем токен в таблице сессий, если
не находим удаляем куку. Если сессия с таки токеном есть, но браузер другой - 
удаляем запись о сессии из таблицы, удаляем куку.
[Иначе возвращаем юзера](https://github.com/gembux2012/jsondoc/blob/master/protected/Components/Auth/Identity.php#L56)  
Данный метод позволяет получить юзера в любом месте приложения $user=$this->app->user
  
И еще два метода:  
  -authenticate($name) - проверка имени пользователя при авторизации  и
  -logout() там все просто.
  
##Доступ
Создаем /Component/Controller.php класс [Controller](https://github.com/gembux2012/jsondoc/blob/master/protected/Components/Controller.php#L8)
наследуем от \T4\Mvc\Controller  
перегружаем метод afterAction($action), он будет в любом контроллере 
унаследуемого от данного в любом экшене возвращать юзера.  

И создадим метод error(), который будет возвращать ошибки 401,403  
В фрэймворке есть замечательный метод access($action)
который позволяет разделять доступ к любым контроллерам и экшенам по любому критерию,
но он возвращает только 404 Not Found и 403 Forbidden, чего
вполне достаточно, но не соответствует заданию.

## Контроллер

Создаем контроллер /protected/Controllers/Index.php  
наследуем от ранее созданного /Component/Controller  
Экшены:  
  - login() тут все понятно авторизация  
  - [List()](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L24) и [getList()](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L35)  
  делают одно и тоже, только getList() формально выполняет все задание
  : возвращает [document-list-response.json](https://github.com/gembux2012/jsondoc/blob/master/public/jsondoc/document-list-response.json),
  с пагинацией, телом json документа, ссылкой на документ, время содания
  и модификации, статус, GUID.   
   
   
   Из таблицы documents выбирается
   по [2](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L17) записи (для пагинации ), начиная с номера страницы выбранной в пагинаторе
   на сайте. Только getList() формирует ответ в формате json, в соответствии с заданием, 
   a List() вернет страницу в формате html. 
   Берем схему json прямо из файла [document-list-response.json](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L43)  
   и заполняем значениями из [таблицы documents](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L43)  
   и значениями для [пагинации](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L58).  
   Вызываем экшен   $.get('//jsondoc/GetList.json?=1' здесь ".json" 
   означает что экшен вернет данные в формате json.  
   Можно было бы создавать элементы страницы прямо из json ответа, но
   это безумный нечитабельный код на js(я не профессионал может это и не так)
   поэтому экшен List() отдает то же самое во вьюер
   [List.html](https://github.com/gembux2012/jsondoc/blob/master/protected/Templates/Index/List.html#L9), где  с помощью
   twig все просто и красиво отрисовывается. 
   
   -[Edit()](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L81)  
   Проверяем, создает пользователь новый документ, или запрашивает на редактирование
   [существующий](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L85)  
   [Если документ опубликован, возвращаем 403](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L90)  
   [Если пользователь не является владельцем документа, возвращаем 403](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L89)  
   [Если пользователь не авторизован, возвращаем 401](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L83  )  
   
   
   -[Save()](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L109)  
   [Устанавливаем тайм зону по Гринвичу.](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L111)
   Прверяем, новая запись, или существующая.
   соответственно [создаем новую запись](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L116) или получаем из таблицы [существующую.](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L113)]
   Получаем со страницы тайм зону пользователя и прибавляем к текущему по гринвичу,
   таким образом время создания или модификации документа, будет реальным временем пользователя.
   [Если документ публикуется, устанавливаем признак публикации.](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L122)  
   [Заполняем оставшиеся поля из post.](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L125)  
   [Сохраняем запись.](https://github.com/gembux2012/jsondoc/blob/master/protected/Controllers/Index.php#L127)
   
   action403($message),action404($message) будут [вызваны](https://github.com/gembux2012/jsondoc/blob/master/protected/Components/Controller.php#L23)  
    при нарушении прав доступа.
    
** Фронтэнд
Верстка на BOOTSTRAP и tweeg
Я плохо понимаю безумный js и может все нужно делать совсем по другому
, но тем не менее все работает.  
Обновление страниц происходит без перерезагузки методом .Load(),
описывать создание фронтэнда не требовалось, поэтому я и не буду, там все очень просто.


  

      
   
     
   
         

  


  


   
  


  
  
  