# Создание и редактирование JSON

<!-- BADGES/ -->
Прриложение создано на основе фрэймворка 
[T4](https://github.com/pr-of-it/t4)

- [Demo](#demo)
- [Приступая к разработке](#Приступая-к-разработке)
  - [Создание таблиц](#Создание-таблиц)
  - [Авторизация](#Авторизация)
  - [Доступ](#Доступ)
    - [Контроллер](#Контроллер)
      - [Методы GetList(), List()](#методы-GetList(),-List())
      

## Быстрый старт
1. [Install composer](https://getcomposer.org)
2. [Install docker](https://docs.docker.com/install/)
3. [Install docker-compose](https://docs.docker.com/compose/install/)
4. Run 
    ```bash
    composer create-project yii2-starter-kit/yii2-starter-kit myproject.com --ignore-platform-reqs
    cd myproject.com
    composer run-script docker:build
    ```
5. Go to [http://yii2-starter-kit.localhost](http://yii2-starter-kit.localhost)

## Приступая к разработке
 Не являсь профессиональным веб-разработчиком, никак не мог понять в каком случае
 может понадобиться пользователю на сайте создавать и редактировать
 JSON.  
 Так же много времени заняло освоение Docker-a, поскольку 
 я им не разу не пользовался.  
 
 Для начала в соответствии с экоситемой фрэймворка создаем в 
 /protected директории:  
  -Commands - работа с командной строки  
  -Components/Auth - авторизация  
  -Controllers - дефолтный контроллер  
  -Models - модели таблиц  
  -Templates/Index - вьюеры экшенов контроллера  
 
## Создание таблиц
 
 В фрэймворке существует механизм миграций, но в данном случае я его не использую.
 Создадим в /Commands класс [CreateTables унаследованный от Command](https://github.com/gembux2012/jsondoc/blob/master/protected/Commands/CreateTables.php#L10)
 c экшеном Init().  
 Описываем создание 3 таблиц на SQL:  
  -users - пользователи  
  -documents - документы json созданные пользователями  
  -sessions - для хранения сессий  
  Назначение полей понятно из названий.    
  Используем ORM фрэймворка.
  Метод [execute()](https://github.com/gembux2012/jsondoc/blob/master/protected/Commands/CreateTables.php#L37)- создает таблицы.  
  Создаем [пользователя](https://github.com/gembux2012/jsondoc/blob/master/protected/Commands/CreateTables.php#L41).  
  Командой php /protected/t4.php CreateTables/Init будут созданы таблицы и пользователь
  root.
  
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
  делают одно и тоже. Из таблицы documents выбирается
   по 5 записей(для пагинации), начиная с номера страницы выбранной в пагинаторе
   на сайте. Только getList() формирует ответ в формате json, в соответствии с заданием. 
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
   Соответственно getEdit() делает то же самое но в формате json.
   
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
Верстка BOOTSTRAP  
[Index.html](https://github.com/gembux2012/jsondoc/blob/master/protected/Layouts/Index.html)  

      
   
     
   
         

  


  


   
  


  
  
  
  
  
  
### I18N
- Built-in translations:
    - English
    - Spanish
    - Russian
    - Ukrainian
    - Chinese
    - Vietnamese
    - Polish
    - Portuguese (Brazil)
- Language switcher, built-in behavior to choose locale based on browser preferred language
- Backend translations manager

### Users
- Sign in
- Sign up
- Profile editing(avatar, locale, personal data)
- Optional activation by email
- OAuth authorization
- RBAC with predefined `guest`, `user`, `manager` and `administrator` roles
- RBAC migrations support

### Development
- Ready-to-use Docker-based stack (php, nginx, mysql, mailcatcher)
- .env support
- [Webpack](https://webpack.js.org/) build configuration
- Key-value storage service
- Ready to use REST API module
- [File storage component + file upload widget](https://github.com/trntv/yii2-file-kit)
- On-demand thumbnail creation [trntv/yii2-glide](https://github.com/trntv/yii2-glide)
- Built-in queue component [yiisoft/yii2-queue](https://github.com/yiisoft/yii2-queue)
- Command Bus with queued and async tasks support [trntv/yii2-command-bus](https://github.com/trntv/yii2-command-bus)
- `ExtendedMessageController` with ability to replace source code language and migrate messages between message sources
- [Some useful shortcuts](https://github.com/yii2-starter-kit/yii2-starter-kit/blob/master/common/helpers.php)

### Other
- Useful behaviors (GlobalAccessBehavior, CacheInvalidateBehavior)
- Maintenance mode support ([more](#maintenance-mode))
- [Aceeditor widget](https://github.com/trntv/yii2-aceeditor)
- [Datetimepicker widget](https://github.com/trntv/yii2-bootstrap-datetimepicker), 
- [Imperavi Reactor Widget](https://github.com/asofter/yii2-imperavi-redactor), 
- [Xhprof Debug panel](https://github.com/trntv/yii2-debug-xhprof)
- Sitemap generator
- Extended IDE autocompletion
- Test-ready
- Docker support and Vagrant support
- Built-in [mailcatcher](http://mailcatcher.me/)
- many other features i'm lazy to write about :-)

## DEMO
Demo is hosted by awesome [Digital Ocean](https://m.do.co/c/d7f000191ea8)
- Frontend: [http://yii2-starter-kit.terentev.net](http://yii2-starter-kit.terentev.net)
- Backend: [http://backend.yii2-starter-kit.terentev.net](http://backend.yii2-starter-kit.terentev.net)

`administrator` role account
```
Login: webmaster
Password: webmaster
```

`manager` role account
```
Login: manager
Password: manager
```

`user` role account
```
Login: user
Password: user
```

## How to contribute?
You can contribute in any way you want. Any help appreciated, but most of all i need help with docs (^_^)

## Have any questions?
Mail to [eugene@terentev.net](mailto:eugene@terentev.net)

## READ MORE
- [Yii2](https://github.com/yiisoft/yii2/tree/master/docs)
- [Docker](https://docs.docker.com/get-started/)


### NOTE
This template was created mostly for developers NOT for end users.
This is a point where you can start your application, rather than creating it from scratch.
Good luck!

