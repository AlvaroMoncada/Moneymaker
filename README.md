# 💼 Moneymaker

Moneymaker es una aplicación web integral para la **gestión y control de clientes, ventas y comisiones**, diseñada para ofrecer una experiencia fluida, reactiva y moderna tanto para el equipo comercial como para la administración.

---

## 🚀 Tecnologías y Frameworks

### **Backend**
- **Lenguaje:** [PHP](https://www.php.net/) (v8.2+)
- **Framework:** [Laravel 11](https://laravel.com/)
- **Autenticación:** Laravel Breeze & Laravel Sanctum
- **Base de Datos:** SQLite (por defecto en local) / Compatible con MySQL y PostgreSQL

### **Frontend**
- **Lenguaje:** [JavaScript](https://developer.mozilla.org/es/docs/Web/JavaScript) (ES Modules)
- **Framework:** [Vue 3](https://vuejs.org/) (Composition API)
- **SPA Bridge:** [Inertia.js](https://inertiajs.com/) (permite crear SPAs sin necesidad de construir una API REST separada)
- **Estilos:** [Tailwind CSS](https://tailwindcss.com/)
- **Bundler / Herramienta de compilación:** [Vite](https://vitejs.dev/)
- **Métricas y Gráficos:** [Chart.js](https://www.chartjs.org/) & [vue-chartjs](https://vue-chartjs.org/)
- **Componentes adicionales:** Select2 / jQuery

---

## ✨ Características Principales

- 👥 **Gestión de Clientes:** Altas, bajas, modificaciones y seguimiento de información de contacto.
- 📈 **Control de Ventas:** Registro detallado de ventas realizadas asociadas a clientes y usuarios.
- 💰 **Cálculo y Liquidación de Comisiones:** Seguimiento transparente de las comisiones generadas por venta.
- 📊 **Panel y Visualización Gráfica:** Gráficos interactivos de rendimiento y ventas mediante Chart.js.
- 🔐 **Autenticación y Seguridad:** Control de acceso, registro, login y gestión de perfil de usuario.

---

## 📋 Requisitos Previos

Asegúrate de contar con las siguientes herramientas instaladas en tu entorno local:

- **PHP** >= 8.2 (con extensiones comunes: `pdo_sqlite`, `curl`, `mbstring`, `openssl`, etc.)
- **Composer** >= 2.x ([Descargar Composer](https://getcomposer.org/))
- **Node.js** >= 18.x y **npm** ([Descargar Node.js](https://nodejs.org/))
- **Git** ([Descargar Git](https://git-scm.com/))

---

## 🛠️ Guía de Despliegue en Local

Sigue estos pasos ordenados para clonar, configurar y ejecutar el proyecto en tu máquina:

### 1. Clonar el repositorio
```bash
git clone https://github.com/AlvaroMoncada/Moneymaker.git
cd Moneymaker
```

### 2. Instalar dependencias del Backend (PHP)
```bash
composer install
```

### 3. Instalar dependencias del Frontend (Node)
```bash
npm install
```

### 4. Configurar el archivo de entorno
Copia la plantilla `.env.example` para generar tu archivo `.env`:

**En Linux / macOS:**
```bash
cp .env.example .env
```

**En Windows (PowerShell):**
```powershell
Copy-Item .env.example .env
```

### 5. Generar la clave de la aplicación
```bash
php artisan key:generate
```

### 6. Configurar la Base de Datos y ejecutar migraciones

Tienes dos opciones según cómo prefieras trabajar:

#### **Opción A: Sin Laragon (SQLite - Rápido y sin instalar servidores)**
Laravel 11 incluye soporte directo para SQLite sin necesidad de instalar ni arrancar MySQL o Laragon.

1. Si no existe el archivo de base de datos, créalo:
   - **En Windows (PowerShell):**
     ```powershell
     New-Item -ItemType File -Path database\database.sqlite -Force
     ```
   - **En Linux / macOS:**
     ```bash
     touch database/database.sqlite
     ```
2. En tu `.env`, asegúrate de tener:
   ```env
   DB_CONNECTION=sqlite
   ```

#### **Opción B: Con Laragon / XAMPP (MySQL)**
Si prefieres usar MySQL con **Laragon**:
1. Inicia **Laragon** y pulsa en **"Start All"** (para arrancar MySQL).
2. Crea una base de datos (por ejemplo, llamada `moneymaker` o `ProyectoDaw`) desde phpMyAdmin o HeidiSQL.
3. En tu archivo `.env`, configura la conexión:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ProyectoDaw
   DB_USERNAME=root
   DB_PASSWORD=
   ```

---

#### Ejecutar las migraciones
Una vez configurada la base de datos (SQLite o MySQL), ejecuta:
```bash
php artisan migrate
```
*(Opcional: puedes ejecutar `php artisan migrate --seed` si dispones de datos de prueba).*

### 7. Iniciar los servidores de desarrollo

Necesitarás dos terminales abiertas:

**Terminal 1 — Servidor Backend (Laravel):**
```bash
php artisan serve
```
El servidor backend se ejecutará en: [http://localhost:8000](http://localhost:8000)

**Terminal 2 — Servidor Frontend (Vite):**
```bash
npm run dev
```

¡Listo! Abre tu navegador en [http://localhost:8000](http://localhost:8000) para acceder a Moneymaker.

---

## 🧪 Pruebas Unitarias y Análisis

Para ejecutar los tests automatizados:
```bash
php artisan test
# o bien
./vendor/bin/phpunit
```

Para dar formato y validar estándares de código PHP con Laravel Pint:
```bash
./vendor/bin/pint
```

---

## 📦 Construcción para Producción

Para compilar y minificar los assets del frontend antes de pasar a producción:
```bash
npm run build
```

---

## 📂 Estructura del Proyecto

```text
Moneymaker/
├── app/
│   ├── Http/Controllers/    # Controladores (Client, Sell, Comission, Profile...)
│   └── Models/                 # Modelos Eloquent (Client, Sell, Comission, User)
├── database/
│   ├── migrations/            # Estructura y evolución de la base de datos
│   └── seeders/               # Datos iniciales
├── resources/
│   ├── js/
│   │   ├── Pages/             # Vistas Vue 3 por módulo (Clients, Sells, Comissions...)
│   │   ├── Components/        # Componentes reutilizables
│   │   └── app.js             # Punto de entrada frontend e Inertia
│   └── css/                   # Estilos Tailwind CSS
├── routes/
│   ├── web.php                # Rutas web protegidas con autenticación
│   └── auth.php               # Rutas de autenticación
└── vite.config.js             # Configuración de empaquetado Vite
```

---

## 📄 Licencia

Este proyecto se distribuye bajo la licencia [MIT](https://opensource.org/licenses/MIT).
