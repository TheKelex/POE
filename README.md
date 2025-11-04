# POE — Plataforma Escolar

Este repositorio contiene la totalidad del sitio web "POE" (Plataforma de Organización Escolar). Está desarrollado en PHP con HTML/CSS y usa Bootstrap para estilos. La base de datos está incluida en `DB/poe.sql`.

## Contenido del repositorio

- `index.php` — Página principal pública.
- `style.css` — Estilos globales.
- `bootstrap-5.3.7-dist/` — Librería Bootstrap (CSS y JS).
- `DB/poe.sql` — Volcado de la base de datos MySQL requerida por la aplicación.
- `EDITOR_INDEX/` — Panel/edición del index y sus utilidades.
- `PRIVADO/` — Área administrativa / privada (múltiples módulos: egresados, estudiantes, sesiones, etc.).
- `PUBLICO/` — Páginas públicas (escuela de padres, talleres, líneas de atención, etc.).
- `Imagenes/`, `PRIVADO/.../FOTOS/`, `PRIVADO/.../uploads/` — Imágenes y directorios de subida de archivos usados por la aplicación.

> Nota: la estructura completa está en la raíz del proyecto. Muchas páginas incluyen conexiones a la base de datos con credenciales por defecto (ver la sección "Credenciales y configuración").

## Requisitos

- XAMPP (o similar) con Apache y MySQL/MariaDB.
- PHP (versión compatible con su XAMPP instalado). El proyecto usa funciones MySQLi/estándar de PHP.
- Un navegador web moderno.

## Instalación y puesta en marcha (Windows + XAMPP)

1. Copia la carpeta del proyecto dentro de la carpeta `htdocs` de XAMPP. Por ejemplo:

   - `C:\xampp\htdocs\POE` (ya está en esta ubicación en tu entorno de desarrollo).

2. Inicia los servicios Apache y MySQL desde el panel de control de XAMPP.

3. Importa la base de datos MySQL:

   Opción A — phpMyAdmin:
   - Abre `http://localhost/phpmyadmin`
   - Crea una base de datos nueva llamada `poe` (si deseas mantener el mismo nombre que en el volcado).
   - Selecciona la base de datos y usa la pestaña "Importar" para subir `DB/poe.sql`.

   Opción B — Línea de comandos (si tienes `mysql` en PATH o usando la ruta de XAMPP):

```powershell
# Usando el cliente MySQL de XAMPP
C:\xampp\mysql\bin\mysql.exe -u root < "C:\xampp\htdocs\POE\DB\poe.sql"

# Si tu usuario root tiene contraseña, usa:
C:\xampp\mysql\bin\mysql.exe -u root -p poe < "C:\xampp\htdocs\POE\DB\poe.sql"
```

4. Abre en el navegador la aplicación:

   - `http://localhost/POE/index.php`

## Credenciales y configuración de la base de datos

En este proyecto muchas páginas usan la conexión MySQLi fija con los siguientes valores por defecto encontrados en el código:

- Host: `localhost`
- Usuario: `root`
- Contraseña: (cadena vacía) — `""`
- Base de datos: `poe`

Archivos de ejemplo donde se encontró la conexión (no es exhaustivo):

- `index.php`
- `PRIVADO/INICIO SESION/login.php`
- `PRIVADO/estudiantes.php`
- `PRIVADO/listas_egresados.php`
- `PRIVADO/ESTUDIANTES/FOLDER/folder.php`
- `PUBLICO/LINEAS_ATENCION/lineas_atencion.php`
- Múltiples scripts en `PRIVADO/EGRESADOS/`, `PRIVADO/ESTUDIANTES/`, `PRIVADO/EDIT/FORM_FORO/`, etc.

Si tu servidor MySQL usa credenciales distintas, puedes:

- Editar las líneas con `new mysqli("localhost", "root", "", "poe")` o `mysqli_connect(...)` en los archivos mencionados y reemplazar `root` y la contraseña por tus credenciales.

Recomendación de mejora: centralizar la configuración de la base de datos en un solo archivo (por ejemplo `config/db.php`) y requerirlo desde cada script en lugar de hardcodear la conexión en cada archivo.

### Búsqueda rápida de conexiones (PowerShell)

Puedes listar los archivos PHP que contienen `new mysqli` o `mysqli_connect` con:

```powershell
Get-ChildItem -Recurse -Filter *.php | Select-String -Pattern "new mysqli|mysqli_connect" | Select-Object Path -Unique
```

Y reemplazar credenciales con un editor o script si fuera necesario.

## Permisos y subida de archivos

- Asegúrate de que las carpetas que almacenan archivos subidos (por ejemplo `PRIVADO/.../uploads`, `Imagenes/`, `PRIVADO/.../FOTOS/`) sean escribibles por el usuario que ejecuta Apache. En Windows esto rara vez requiere cambios explícitos, pero en Linux sería necesario ajustar permisos/propietario.

## Seguridad y recomendaciones

- Evita usar el usuario `root` para la operación normal. Crea un usuario MySQL limitado con solo los privilegios necesarios para la aplicación.
- Mueve las credenciales a un archivo fuera del árbol público (por ejemplo `config/`) y usa `include 'config/db.php'`.
- Valida y sanea todas las entradas del usuario para prevenir inyección SQL y XSS. Muchas páginas usan consultas directas; revisar y migrar a consultas preparadas (prepared statements) es recomendable.

## Estructura destacada y puntos de entrada

- Punto de entrada público: `index.php`
- Panel/edición de índice: `EDITOR_INDEX/editor index.php`
- Área administrativa y scripts sensibles: carpeta `PRIVADO/` — restringir el acceso solo a usuarios autenticados.

## Próximos pasos sugeridos (mejoras de bajo riesgo)

- Crear un archivo de configuración central para la DB (`config/db.php`) y actualizar los archivos para incluirlo.
- Añadir un pequeño script `check.php` que verifique la conexión a la base de datos y muestre un estado (útil para debugging).
- Añadir documentación adicional por módulo si se desea (por ejemplo, explicación de tablas principales en `DB/poe.sql`).

## Contacto / Soporte

Si necesitas que genere:

- Un archivo `config/db.php` centralizado y aplique los cambios a los scripts existentes.
- Un script de importación automatizado.

Dímelo y lo implemento.

---

README generado automáticamente — contiene instrucciones para ejecutar la aplicación localmente y para importar `DB/poe.sql`.
