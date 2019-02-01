# Мобильное тестирование imkosmetik.com

#### Установка
- Запустить `composer install` из корневой директории

#### Запуск тестов
`./start_tests.bash test path/to/test.php` - проверит, работает ли Appium (если нет, то автоматически попробует его стартануть), и запустит указанный тест. Поддерживаемые параметры:
- вызов без параметров покажет краткую справку
- `test path/to/test1.php path/to/test2.php` выполняет указанные тесты
- `--tests path/to/testDirectory1/ path/to/testDirectory2/` выполняет все тесты в указанных директориях
- `-t` для указания времени ожидания ответа Appium
- `-q` для запуска в тихом режиме (не будут задаваться вопросе о попытке переподключения)
- Для передачи параметров appium нужно дописать их с флагом `--appium`, для передачи параметров phpunit - `--phpunit`. 
<br>Пример с запуском всех возможных команд: `./start_tests.bash -q -t 30 —appium "--address 0.0.0.0 —port 4723" —phpunit "--verbose" test path/to/test1.php path/to/test2.php —tests path/to/testDirectory1/ path/to/testDirectory2/`

#### Проблемы 
- ```Ошибка : '12.0' does not exist in the list of simctl SDKs. Only the following Simulator SDK versions are available on your system: x.y</p>```
- По умолчанию тесты ожидают, что версия IOS будет 12.0 
- Если 12.0 недоступна на вашей системе - измените версию, установив переменную окружения `IOS_PLATFORM_VERSION` или с помощью Xcode

#### Документация
- http://appium.io/ - официальный сайт Appium (можно посмотреть синтаксис тестов, команд серверу и т.п.)
  - http://appium.io/docs/en/writing-running-appium/server-args/ - команды для appium (можно задать вывод в лог, колиечство переподключений и т. п.) 
- https://github.com/appium/appium/tree/master/sample-c.. - небольшие примеры тестов от разработчика Appium
