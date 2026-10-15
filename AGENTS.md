# 🤖 Guía e Instrucciones para el Agente (misVentas)

Este documento define las reglas de desarrollo, arquitectura y directrices de trabajo para cualquier agente de IA que interactúe con el repositorio **misVentas**.

---

## 🏛️ Arquitectura y Stack Tecnológico

- **Backend:** Laravel 11, PHP 8.2+
- **Frontend:** Vue 3 (Composition API con `<script setup>`), Inertia.js (SPA sin API REST desacoplada)
- **Estilos:** Tailwind CSS
- **Herramienta de compilación:** Vite
- **Autenticación:** Laravel Breeze con sesiones protegidas por middleware `auth`
- **Base de Datos:** SQLite / MySQL con migraciones y modelos Eloquent

---

## 🔒 Reglas de Seguridad y Aislamiento de Datos

1. **Multi-inquilino / Aislamiento por usuario:**
   - Todos los registros principales (`sells`, `clients`) pertenecen a un usuario autenticado (`user_id`).
   - Al consultar datos (`index`, `edit`, `show`), filtrar siempre por el usuario actual:
     ```php
     Model::where('user_id', auth()->id())->...
     ```
   - Al crear (`store`), asignar siempre `'user_id' => auth()->id()`.
   - Al actualizar o eliminar (`update`, `destroy`), verificar pertenencia con `where('user_id', auth()->id())->firstOrFail()` o políticas (Policies) para evitar IDOR (Insecure Direct Object References).

---

## 💼 Reglas de Negocio

- **Ventas (`sells`):**
  - Cada venta contiene `title`, `price`, `client_id`, `user_id` y su porcentaje de `comission` directo.
  - El campo `comission` se maneja como valor numérico/decimal (%) en la propia venta, sin tabla separada de comisiones.
  - La ganancia individual se calcula como: `(price * comission) / 100`.
  - La ganancia total en el dashboard corresponde a la suma acumulada de las ganancias de todas las ventas del usuario.
- **Clientes (`clients`):**
  - Cada cliente está asociado a un usuario (`user_id`).
  - No permitir que un usuario seleccione o asigne clientes de otros usuarios a sus ventas.

---

## 🎨 Convenciones de Frontend (Vue 3 + Inertia + Tailwind)

- **Estructura de Componentes:**
  - Usar siempre `<script setup>` y Composition API.
  - Mantener la coherencia estética con la paleta de colores morada/púrpura (`purple-700`, `purple-800`, `purple-50`, etc.) y fondos limpios.
  - Utilizar los componentes reutilizables existentes en `resources/js/Components/` (`PrimaryButton`, `DangerButton`, `SecondaryButton`, `InputGroup`, `SelectInput`, `Modal`, `Pagination`, etc.).
- **Manejo de Formularios:**
  - Usar `useForm` de `@inertiajs/vue3` para manejar estado reactivo, errores y envíos de formulario.
- **Feedback al usuario:**
  - Mostrar notificaciones temporales de éxito/error tras completar acciones CRUD.

---

## ⚙️ Flujo de Trabajo y Buenas Prácticas

1. **Aprobación Previa Obligatoria mediante Artefactos:**
   - **Antes de modificar o crear cualquier archivo de código**, el agente debe generar un **artefacto** explicativo (plan de ejecución o propuesta detallada de cambios) con `RequestFeedback: true`.
   - El artefacto debe detallar:
     - Archivos afectados y propósito de la modificación.
     - Resumen o diff de los cambios propuestos.
     - Posible impacto en la base de datos o en la interfaz.
   - **Esperar la confirmación y aceptación explícita del usuario** antes de proceder con cualquier modificación de código o ejecución de comandos de escritura.
2. **Migraciones:**
   - Crear nuevas migraciones en `database/migrations/` para cualquier cambio de esquema. No modificar migraciones antiguas ya ejecutadas salvo durante setup inicial.
3. **Validación:**
   - Toda petición HTTP de escritura (`store`, `update`) debe validar exhaustivamente los tipos y rangos de datos en el controlador o en Form Requests dedicados.
4. **Verificación de Frontend:**
   - Tras realizar cambios en componentes Vue o JavaScript, compilar o comprobar con `npm run build` para asegurar que no haya errores de empaquetado o tipos.
5. **Respuestas e Interacciones:**
   - Mantener explicaciones claras y concisas en español cuando el usuario se comunique en dicho idioma.
   - Proponer diffs limpios y no alterar código ajeno a la tarea solicitada.
