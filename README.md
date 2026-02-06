# 📋 Instrucciones de Instalación y Configuración

Este sistema requiere ser instalado en un **servidor Linux** con los siguientes componentes:

- **Apache**
- **PHP**
- **MariaDB**

---

## 📂 Estructura de Carpetas para Imágenes

Dentro de `assets/img/` deben crearse las siguientes carpetas para el almacenamiento de archivos:

- `firmas`
- `fotos_clientes`
- `fotos_colaborador`
- `fotos_desembolso`
- `fotos_garantia`
- `img-avisos`

Ejemplo de estructura esperada:

```
assets/
└── img/
    ├── firmas/
    ├── fotos_clientes/
    ├── fotos_colaborador/
    ├── fotos_desembolso/
    ├── fotos_garantia/
    └── img-avisos/
```

---

## 🌎 Configuración de Zona Horaria

Al instalar el sistema en el servidor, **configurar la zona horaria a Managua, Nicaragua (GMT-6)**.
Esto puede hacerse editando el archivo `/etc/php.ini` y ajustando la línea correspondiente:

```ini
date.timezone = "America/Managua"
```

También debe configurarse la zona horaria del servidor:

```bash
sudo timedatectl set-timezone America/Managua
```

---

## ⏰ Configuración de Cron Jobs

En el servidor Linux deben agregarse las siguientes tareas programadas:

| Minute | Hour | Day of Month | Month | Day of Week | Command                                                                 |
|-------|------|-------------|-------|------------|-------------------------------------------------------------------------|
| 30    | 22   | *           | *     | *          | `curl https://domioinstalado.net/croninsercion.php?action=InsercionCeros` |
| 00    | 4    | *           | *     | *          | `curl https://domioinstalado.net/cronmetadia.php?action=getMetasDelDiaPorColaborador` |

Puedes editar el cron con:

```bash
crontab -e
```

y agregar estas líneas:

```bash
30 22 * * * curl https://domioinstalado.net/croninsercion.php?action=InsercionCeros
00 4  * * * curl https://domioinstalado.net/cronmetadia.php?action=getMetasDelDiaPorColaborador
```

---

## 📝 Sistema de Logs

El sistema genera registros de actividad en archivos de texto y JSON.
Estos logs pueden ser revisados para auditoría o resolución de problemas:

- `log-general.txt`
- `log-general-json.txt`
- `logfile.txt`
- `logfileFiador.txt`

Es recomendable revisar periódicamente estos archivos para detectar errores o incidencias.

---

## ✅ Recomendaciones

- Verificar permisos de escritura en las carpetas de imágenes.
- Asegurar que `cron` esté activo en el servidor.
- Configurar backups automáticos de los archivos de log si son críticos.
