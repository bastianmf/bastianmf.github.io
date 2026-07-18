<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        return view('portfolio.index', [
            'filters' => $this->filters(),
            'projects' => $this->projects(),
            'skills' => $this->skills(),
            'process' => $this->process(),
        ]);
    }

    /**
     * Pasos de la metodología. El orden define el escalonado de la arcada.
     *
     * @return array<int, array{number: string, title: string, text: string}>
     */
    private function process(): array
    {
        return [
            [
                'number' => '01',
                'title' => 'Descubrimiento',
                'text' => 'Comprensión del problema, los usuarios, los objetivos comerciales y las necesidades del proyecto.',
            ],
            [
                'number' => '02',
                'title' => 'Estrategia',
                'text' => 'Definición de estructura, funcionalidades, tecnologías, flujos y lineamientos visuales.',
            ],
            [
                'number' => '03',
                'title' => 'Desarrollo',
                'text' => 'Construcción de la interfaz, lógica del sistema, integraciones y funcionalidades principales.',
            ],
            [
                'number' => '04',
                'title' => 'Lanzamiento',
                'text' => 'Pruebas, optimización, despliegue y acompañamiento durante la puesta en producción.',
            ],
        ];
    }

    /**
     * Filtros del listado de proyectos.
     *
     * @return array<int, array{key: string, label: string}>
     */
    private function filters(): array
    {
        return [
            ['key' => 'all', 'label' => 'Todos'],
            ['key' => 'web', 'label' => 'Sitios web'],
            ['key' => 'system', 'label' => 'Sistemas'],
            ['key' => 'design', 'label' => 'Diseño UI'],
        ];
    }

    /**
     * Obras seleccionadas.
     *
     * numeral     Numeral romano de la píldora recortada. Es la clave que une
     *             el botón de la card con su panel de detalle.
     * category    Debe coincidir con el `key` de un filtro.
     * image       Variante de degradado de respaldo (one|two|three|four) que
     *             se usa si un proyecto no tiene galería.
     * summary     Texto largo del panel de detalle.
     * facts       Pares dato/valor del panel (3 se ven bien).
     * gallery     Capturas del proyecto: cada una con img, title y caption. La
     *             primera es la portada de la card; todas forman las pestañas de
     *             imagen (image tabs) del panel de detalle.
     * url         Enlace al proyecto ('#' mientras no exista).
     *
     * @return array<int, array{numeral: string, category: string, image: string, kicker: string, title: string, description: string, summary: string, facts: array<int, array{k: string, v: string}>, gallery: array<int, array{img: string, title: string, caption: string}>, tags: array<int, string>, url: string}>
     */
    private function projects(): array
    {
        return [
            [
                'numeral' => 'I',
                'category' => 'system',
                'image' => 'one',
                'kicker' => 'Sistema empresarial',
                'title' => 'Sistema Cotizador 2026',
                'description' => 'Plataforma para administrar cotizaciones, productos, márgenes, proveedores, ventas y procesos internos.',
                'summary' => 'Un sistema pensado para reemplazar planillas: centraliza cotizaciones, productos, márgenes y proveedores en un solo lugar, con control de estados y trazabilidad de cada venta. La interfaz prioriza el trabajo repetido: buscar, comparar, actuar y cerrar sin saltos.',
                'facts' => [
                    ['k' => 'Rol', 'v' => 'Desarrollo full stack'],
                    ['k' => 'Foco', 'v' => 'Procesos internos y márgenes'],
                    ['k' => 'Stack', 'v' => 'Laravel · MySQL · APIs REST'],
                ],
                'gallery' => [
                    ['img' => 'images/proyectos/cotizador/1.jpg', 'title' => 'Panel de cotizaciones', 'caption' => 'Vista general con métricas, listado y dólar referencial para seguir cada cotización.'],
                    ['img' => 'images/proyectos/cotizador/2.jpg', 'title' => 'Detalle de la cotización', 'caption' => 'Desglose de productos, cantidades y totales de una cotización.'],
                    ['img' => 'images/proyectos/cotizador/3.jpg', 'title' => 'Armado de la cotización', 'caption' => 'Ítems propuestos, resumen y datos de despacho y pago al crearla.'],
                    ['img' => 'images/proyectos/cotizador/4.jpg', 'title' => 'Documento final', 'caption' => 'La cotización lista para enviar, en formato de documento formal.'],
                ],
                'tags' => ['Laravel', 'MySQL', 'APIs'],
                'url' => '#',
            ],
            [
                'numeral' => 'II',
                'category' => 'web',
                'image' => 'two',
                'kicker' => 'Ecommerce',
                'title' => 'SC Informática',
                'description' => 'Catálogo tecnológico integrado con proveedores, automatización de precios y procesos de venta.',
                'summary' => 'Catálogo tecnológico conectado a proveedores, con actualización automática de precios y stock. Integra despacho y medios de pago para que el flujo de compra funcione de punta a punta sin intervención manual.',
                'facts' => [
                    ['k' => 'Rol', 'v' => 'Desarrollo e integraciones'],
                    ['k' => 'Foco', 'v' => 'Automatización de precios'],
                    ['k' => 'Stack', 'v' => 'Laravel · Chilexpress · Pagos'],
                ],
                'gallery' => [
                    ['img' => 'images/proyectos/ecommerce-sc/1.jpg', 'title' => 'Portada de la tienda', 'caption' => 'Home con hero de destacados y accesos a las categorías principales.'],
                    ['img' => 'images/proyectos/ecommerce-sc/2.jpg', 'title' => 'Productos por categoría', 'caption' => 'Vista de una categoría con destacados y carrusel de productos.'],
                    ['img' => 'images/proyectos/ecommerce-sc/3.jpg', 'title' => 'Explorar por categoría', 'caption' => 'Grid de categorías para encontrar lo que se busca rápido.'],
                    ['img' => 'images/proyectos/ecommerce-sc/4.jpg', 'title' => 'Servicios TI', 'caption' => 'Bloques de servicios técnicos y asesoría, además del catálogo.'],
                    ['img' => 'images/proyectos/ecommerce-sc/5.jpg', 'title' => 'Catálogo con filtros', 'caption' => 'Listado de periféricos con filtros laterales y grilla de productos.'],
                    ['img' => 'images/proyectos/ecommerce-sc/6.jpg', 'title' => 'Ficha de producto', 'caption' => 'Detalle de un producto con precios, stock y opciones de compra.'],
                    ['img' => 'images/proyectos/ecommerce-sc/7.jpg', 'title' => 'Cuenta del cliente', 'caption' => 'Perfil, favoritos y datos personales del usuario.'],
                    ['img' => 'images/proyectos/ecommerce-sc/8.jpg', 'title' => 'Checkout', 'caption' => 'Elección de medio de pago y resumen de compra, también en móvil.'],
                ],
                'tags' => ['Ecommerce', 'Chilexpress', 'Pagos'],
                'url' => '#',
            ],
            [
                'numeral' => 'III',
                'category' => 'design',
                'image' => 'three',
                'kicker' => 'Rediseño frontend',
                'title' => 'SoyTrabajador · Rediseño 2026',
                'description' => 'Renovación visual de una plataforma de orientación laboral, utilizando una propuesta editorial moderna, accesible y alineada con su identidad institucional.',
                'summary' => 'Renovación visual completa de una plataforma de orientación laboral. La propuesta editorial busca que un tema difícil se lea con calma: jerarquía tipográfica clara, secciones que respiran e interacciones que orientan en vez de decorar.',
                'facts' => [
                    ['k' => 'Rol', 'v' => 'Diseño UI y frontend'],
                    ['k' => 'Foco', 'v' => 'Lectura y accesibilidad'],
                    ['k' => 'Stack', 'v' => 'Blade · CSS · JavaScript'],
                ],
                'gallery' => [
                    ['img' => 'images/proyectos/soytrabajador/1.jpg', 'title' => 'Portada', 'caption' => 'Hero editorial: “Defendemos sólo a quien trabaja”.'],
                    ['img' => 'images/proyectos/soytrabajador/2.jpg', 'title' => 'Áreas de práctica', 'caption' => 'Las materias del derecho laboral que atiende el estudio.'],
                    ['img' => 'images/proyectos/soytrabajador/3.jpg', 'title' => 'Manifiesto', 'caption' => 'Sección de posición y compromiso del estudio.'],
                    ['img' => 'images/proyectos/soytrabajador/4.jpg', 'title' => 'Detalle de servicio', 'caption' => 'Cada materia con su numeral y explicación.'],
                    ['img' => 'images/proyectos/soytrabajador/5.jpg', 'title' => 'Ficha a pantalla completa', 'caption' => 'El panel que baja al abrir un servicio.'],
                    ['img' => 'images/proyectos/soytrabajador/6.jpg', 'title' => 'Honorarios y dudas', 'caption' => 'Sección de honorarios y preguntas frecuentes.'],
                ],
                'tags' => ['UI Design', 'Frontend', 'UX'],
                'url' => '#',
            ],
            [
                'numeral' => 'IV',
                'category' => 'system',
                'image' => 'four',
                'kicker' => 'Aplicación web',
                'title' => 'Plataforma SPA Offline',
                'description' => 'Aplicación para realizar encuestas, almacenar audios y sincronizar información desde zonas sin conexión.',
                'summary' => 'Aplicación para levantar encuestas en terreno donde no hay señal: guarda respuestas y audios en el dispositivo y sincroniza cuando vuelve la conexión. El desafío real no fue el formulario, sino la sincronización y no perder nada.',
                'facts' => [
                    ['k' => 'Rol', 'v' => 'Desarrollo full stack'],
                    ['k' => 'Foco', 'v' => 'Trabajo sin conexión'],
                    ['k' => 'Stack', 'v' => 'Python · Flask · Whisper'],
                ],
                'gallery' => [
                    ['img' => 'images/proyectos/encuestas-spa/1.jpg', 'title' => 'Panel administrativo', 'caption' => 'Gestión y seguimiento comunitario con métricas de comuneros y encuestas.'],
                    ['img' => 'images/proyectos/encuestas-spa/2.jpg', 'title' => 'Inicio de encuesta', 'caption' => 'Ingreso por RUT y datos del encuestado antes de comenzar.'],
                    ['img' => 'images/proyectos/encuestas-spa/3.jpg', 'title' => 'Aplicación de la encuesta', 'caption' => 'Preguntas por sección con respuestas que se van guardando.'],
                    ['img' => 'images/proyectos/encuestas-spa/4.jpg', 'title' => 'Panel estadístico', 'caption' => 'KPIs, cobertura y perfil del encuestado.'],
                    ['img' => 'images/proyectos/encuestas-spa/5.jpg', 'title' => 'Gráficos', 'caption' => 'Estadísticas demográficas y de vivienda en gráficos.'],
                    ['img' => 'images/proyectos/encuestas-spa/6.jpg', 'title' => 'Exporte en PDF', 'caption' => 'Los gráficos y resultados listos para descargar.'],
                    ['img' => 'images/proyectos/encuestas-spa/7.jpg', 'title' => 'Exporte en Excel', 'caption' => 'Indicadores clave exportados a planilla.'],
                ],
                'tags' => ['Python', 'Flask', 'Whisper'],
                'url' => '#',
            ],
            [
                'numeral' => 'V',
                'category' => 'web',
                'image' => 'one',
                'kicker' => 'Plataforma informativa',
                'title' => 'Secmap SPA',
                'description' => 'Diseño y propuesta de plataforma corporativa con servicios, cobertura nacional y contenido multimedia.',
                'summary' => 'Plataforma corporativa que ordena servicios, cobertura nacional y contenido multimedia en una narrativa clara. El objetivo era que una empresa técnica se presentara con la solidez que ya tenía, sin recargar la pantalla.',
                'facts' => [
                    ['k' => 'Rol', 'v' => 'Diseño y desarrollo'],
                    ['k' => 'Foco', 'v' => 'Cobertura y servicios'],
                    ['k' => 'Stack', 'v' => 'Laravel · Blade · CSS'],
                ],
                'gallery' => [
                    ['img' => 'images/proyectos/secmap/1.jpg', 'title' => 'Portada', 'caption' => 'Hero: “Conservación, mantención y apoyo operativo”.'],
                    ['img' => 'images/proyectos/secmap/2.jpg', 'title' => 'Aliado en terreno', 'caption' => 'Propuesta de valor y respaldo operativo de la empresa.'],
                    ['img' => 'images/proyectos/secmap/3.jpg', 'title' => 'Ecosistema operativo', 'caption' => 'Diagrama del flujo de trabajo de punta a punta.'],
                    ['img' => 'images/proyectos/secmap/4.jpg', 'title' => 'Cobertura nacional', 'caption' => 'Mapa de presencia técnica a lo largo de Chile.'],
                    ['img' => 'images/proyectos/secmap/5.jpg', 'title' => 'Faena real', 'caption' => 'Galería de fotografías de trabajo en terreno.'],
                    ['img' => 'images/proyectos/secmap/6.jpg', 'title' => 'Contacto', 'caption' => 'Formulario e información de contacto con mapa.'],
                ],
                'tags' => ['Laravel', 'Responsive', 'Frontend'],
                'url' => '#',
            ],
            [
                'numeral' => 'VI',
                'category' => 'system',
                'image' => 'three',
                'kicker' => 'Sistema clínico',
                'title' => 'Centro Médico de Diálisis',
                'description' => 'Portal clínico privado para gestionar pacientes, fichas médicas, auditorías e informes nefrológicos en un centro de diálisis.',
                'summary' => 'Portal clínico para un centro de diálisis: integra pacientes al flujo de atención, mantiene la ficha médica y genera informes nefrológicos con accesos directos. Todo pensado para que el equipo opere con rapidez sin perder el orden ni la trazabilidad clínica.',
                'facts' => [
                    ['k' => 'Rol', 'v' => 'Desarrollo full stack'],
                    ['k' => 'Foco', 'v' => 'Ficha clínica e informes'],
                    ['k' => 'Stack', 'v' => 'Laravel · MySQL · Blade'],
                ],
                'gallery' => [
                    ['img' => 'images/proyectos/centro-medico/1.jpg', 'title' => 'Portal clínico', 'caption' => 'Inicio con accesos rápidos y módulos principales del centro.'],
                    ['img' => 'images/proyectos/centro-medico/2.jpg', 'title' => 'Listado de pacientes', 'caption' => 'Registro clínico con búsqueda de pacientes.'],
                    ['img' => 'images/proyectos/centro-medico/3.jpg', 'title' => 'Nuevo registro clínico', 'caption' => 'Alta de paciente con su base clínica y plan de atención.'],
                    ['img' => 'images/proyectos/centro-medico/4.jpg', 'title' => 'Ficha del paciente', 'caption' => 'Informes médicos y nefrológicos, evolución y recetas.'],
                ],
                'tags' => ['Salud', 'Laravel', 'MySQL'],
                'url' => '#',
            ],
            [
                'numeral' => 'VII',
                'category' => 'system',
                'image' => 'four',
                'kicker' => 'Órdenes de trabajo',
                'title' => 'Sistema de Órdenes de Trabajo',
                'description' => 'Gestión de órdenes de trabajo con estados, avances, sub-OT, dispositivos, servicios y tiempos reales frente a estimados.',
                'summary' => 'Sistema para administrar órdenes de trabajo de soporte técnico: cada orden lleva su tipo, fechas, dispositivos, servicios, avances y el tiempo real contra el estimado. Los detalles se abren en la misma fila para revisar y actuar sin cambiar de pantalla.',
                'facts' => [
                    ['k' => 'Rol', 'v' => 'Desarrollo full stack'],
                    ['k' => 'Foco', 'v' => 'Trazabilidad de órdenes'],
                    ['k' => 'Stack', 'v' => 'Laravel · MySQL · JavaScript'],
                ],
                'gallery' => [
                    ['img' => 'images/proyectos/sistema-ot/1.jpg', 'title' => 'Detalle de la orden', 'caption' => 'La orden se abre en la misma fila con dispositivos, servicios y tareas.'],
                    ['img' => 'images/proyectos/sistema-ot/2.jpg', 'title' => 'Listado de órdenes', 'caption' => 'Tabla de órdenes con estados, responsables y métricas.'],
                    ['img' => 'images/proyectos/sistema-ot/3.jpg', 'title' => 'Nueva orden', 'caption' => 'Creación de una orden con su información básica y folios.'],
                    ['img' => 'images/proyectos/sistema-ot/4.jpg', 'title' => 'Avances de la orden', 'caption' => 'Acciones rápidas, comentarios y seguimiento de una OT.'],
                    ['img' => 'images/proyectos/sistema-ot/5.jpg', 'title' => 'Documento de la orden', 'caption' => 'La orden en formato de documento con tareas y avances.'],
                ],
                'tags' => ['Laravel', 'MySQL', 'Soporte'],
                'url' => '#',
            ],
            [
                'numeral' => 'VIII',
                'category' => 'web',
                'image' => 'one',
                'kicker' => 'Sitio institucional',
                'title' => 'SoyHonorario.cl',
                'description' => 'Sitio para un estudio de abogados laborales, con calculadora de indemnización, servicios y captación por WhatsApp.',
                'summary' => 'Sitio para un estudio de abogados especializado en trabajadores a honorarios del sector público. La propuesta transmite seriedad y cercanía a la vez, con una calculadora de indemnización como puerta de entrada y contacto directo por WhatsApp.',
                'facts' => [
                    ['k' => 'Rol', 'v' => 'Diseño y desarrollo'],
                    ['k' => 'Foco', 'v' => 'Captación y confianza'],
                    ['k' => 'Stack', 'v' => 'Blade · CSS · JavaScript'],
                ],
                'gallery' => [
                    ['img' => 'images/proyectos/soyhonorarios/1.jpg', 'title' => 'Portada', 'caption' => 'Hero: “Recupera la indemnización que te corresponde”.'],
                    ['img' => 'images/proyectos/soyhonorarios/2.jpg', 'title' => 'Calculadora', 'caption' => 'Cálculo de indemnización paso a paso a partir de tus datos.'],
                    ['img' => 'images/proyectos/soyhonorarios/3.jpg', 'title' => 'Proceso', 'caption' => 'De la primera consulta al pago, explicado por etapas.'],
                    ['img' => 'images/proyectos/soyhonorarios/4.jpg', 'title' => 'Resultado del cálculo', 'caption' => 'Estimación de lo que el trabajador podría recuperar.'],
                    ['img' => 'images/proyectos/soyhonorarios/5.jpg', 'title' => 'Detalle del cálculo', 'caption' => 'Desglose de la indemnización y cotizaciones consideradas.'],
                    ['img' => 'images/proyectos/soyhonorarios/6.jpg', 'title' => 'Cierre y contacto', 'caption' => 'Confirmación del caso y datos de contacto.'],
                ],
                'tags' => ['Frontend', 'Landing', 'UX'],
                'url' => '#',
            ],
        ];
    }

    /**
     * Habilidades, numeradas en romano al estilo editorial.
     *
     * @return array<int, array{0: string, 1: string, 2: string}>
     */
    private function skills(): array
    {
        return [
            ['I', 'Backend Development', 'Laravel, PHP, Flask, Python, APIs REST'],
            ['II', 'Frontend Development', 'HTML, CSS, JavaScript, Blade, Responsive UI'],
            ['III', 'Bases de datos', 'MySQL, modelado, migraciones, consultas'],
            ['IV', 'Integraciones', 'Transbank, Mercado Pago, Chilexpress, Microsoft Graph'],
            ['V', 'Diseño de interfaces', 'UI, UX, prototipos, sistemas visuales'],
            ['VI', 'Infraestructura', 'Hostinger, VPS, Nginx, Gunicorn, CRON'],
        ];
    }
}
