ERROR - 2018-05-15 08:36:39 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 112
ERROR - 2018-05-15 08:40:24 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 112
ERROR - 2018-05-15 08:40:36 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 112
ERROR - 2018-05-15 08:40:38 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 112
ERROR - 2018-05-15 08:44:18 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 112
ERROR - 2018-05-15 08:44:19 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 112
ERROR - 2018-05-15 08:44:23 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 112
ERROR - 2018-05-15 08:48:43 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 115
ERROR - 2018-05-15 08:54:49 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 115
ERROR - 2018-05-15 08:56:23 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 115
ERROR - 2018-05-15 08:56:27 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 115
ERROR - 2018-05-15 08:56:51 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 115
ERROR - 2018-05-15 08:57:02 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 115
ERROR - 2018-05-15 08:58:36 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 115
ERROR - 2018-05-15 08:59:25 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 115
ERROR - 2018-05-15 09:09:20 --> Query error: Unknown column 'cursada.extra' in 'field list' - Invalid query: SELECT `cargo_cursada`.`id`, `cargo_cursada`.`carga_horaria`, `cursada`.`division_id`, CONCAT(curso.descripcion, ' ', division.division) as division, `materia`.`descripcion` as `espacio_curricular`, `cursada`.`extra`
FROM `cargo_cursada`
JOIN `cargo` ON `cargo_cursada`.`cargo_id` = `cargo`.`id`
JOIN `cursada` ON `cargo_cursada`.`cursada_id` = `cursada`.`id`
JOIN `espacio_curricular` ON `cursada`.`espacio_curricular_id` = `espacio_curricular`.`id`
JOIN `materia` ON `espacio_curricular`.`materia_id` = `materia`.`id`
JOIN `division` ON `cursada`.`division_id` = `division`.`id`
JOIN `curso` ON `curso`.`id` = `division`.`curso_id`
WHERE `cargo_cursada`.`cargo_id` = '4110'
ERROR - 2018-05-15 09:09:30 --> Query error: Unknown column 'cursada.extra' in 'field list' - Invalid query: SELECT `cargo_cursada`.`id`, `cargo_cursada`.`carga_horaria`, `cursada`.`division_id`, CONCAT(curso.descripcion, ' ', division.division) as division, `materia`.`descripcion` as `espacio_curricular`, `cursada`.`extra`
FROM `cargo_cursada`
JOIN `cargo` ON `cargo_cursada`.`cargo_id` = `cargo`.`id`
JOIN `cursada` ON `cargo_cursada`.`cursada_id` = `cursada`.`id`
JOIN `espacio_curricular` ON `cursada`.`espacio_curricular_id` = `espacio_curricular`.`id`
JOIN `materia` ON `espacio_curricular`.`materia_id` = `materia`.`id`
JOIN `division` ON `cursada`.`division_id` = `division`.`id`
JOIN `curso` ON `curso`.`id` = `division`.`curso_id`
WHERE `cargo_cursada`.`cargo_id` = '4110'
ERROR - 2018-05-15 09:15:01 --> Severity: Notice --> Undefined offset: 1 C:\xampp\htdocs\gem\app\application\modules\comedor\controllers\Administrar.php 361
ERROR - 2018-05-15 09:15:01 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\gem\app\application\modules\comedor\controllers\Administrar.php 361
ERROR - 2018-05-15 09:15:01 --> Severity: Notice --> Undefined offset: 2 C:\xampp\htdocs\gem\app\application\modules\comedor\controllers\Administrar.php 361
ERROR - 2018-05-15 09:15:01 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\gem\app\application\modules\comedor\controllers\Administrar.php 361
ERROR - 2018-05-15 09:15:28 --> Severity: Notice --> Undefined offset: 1 C:\xampp\htdocs\gem\app\application\modules\comedor\controllers\Administrar.php 361
ERROR - 2018-05-15 09:15:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\gem\app\application\modules\comedor\controllers\Administrar.php 361
ERROR - 2018-05-15 09:15:28 --> Severity: Notice --> Undefined offset: 2 C:\xampp\htdocs\gem\app\application\modules\comedor\controllers\Administrar.php 361
ERROR - 2018-05-15 09:15:28 --> Severity: Notice --> Trying to get property of non-object C:\xampp\htdocs\gem\app\application\modules\comedor\controllers\Administrar.php 361
ERROR - 2018-05-15 10:31:59 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 115
ERROR - 2018-05-15 11:38:36 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 115
ERROR - 2018-05-15 11:46:52 --> Severity: Notice --> Undefined variable: cantidad_alumnos_espera C:\xampp\htdocs\gem\app\application\modules\abono\views\abono_escuela_monto\abono_alumno_listar.php 115
