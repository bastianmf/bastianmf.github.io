<section class="section" id="contacto">
    @php
        $isStaticExport = config('portfolio.static_export', false);
        $contactEmail = trim((string) config('portfolio.contact_email', ''));
        $contactSubject = config('portfolio.contact_subject', 'Nuevo proyecto desde el portafolio');
        $encodedContactSubject = rawurlencode($contactSubject);
        $canSendStaticContact = $contactEmail !== '';
    @endphp

    <div class="container">
        <div class="contact-grid">
            <div>
                <div class="section-header">
                    <div class="eyebrow reveal reveal-left" style="--i: 0">
                        Contacto
                    </div>

                    <h2 class="section-title reveal reveal-left" style="--i: 1">
                        Construyamos algo
                        <span>extraordinario.</span>
                    </h2>

                    <p class="section-description reveal reveal-left" style="--i: 2">
                        Cuéntame sobre tu idea, sistema o proyecto. Podemos
                        transformar una necesidad concreta en una experiencia
                        digital completa.
                    </p>
                </div>

                <div class="contact-details reveal reveal-left" style="--i: 3">
                    <div class="contact-detail">
                        <div class="contact-detail-label">Correo</div>

                        @if ($contactEmail !== '')
                            <a
                                href="mailto:{{ $contactEmail }}"
                                class="contact-detail-value"
                            >
                                {{ $contactEmail }}
                            </a>
                        @else
                            <div class="contact-detail-value">
                                Correo por configurar
                            </div>
                        @endif
                    </div>

                    <div class="contact-detail">
                        <div class="contact-detail-label">Ubicación</div>
                        <div class="contact-detail-value">Santiago, Chile</div>
                    </div>

                    <div class="contact-detail">
                        <div class="contact-detail-label">Disponibilidad</div>

                        <div class="contact-detail-value">
                            Proyectos freelance y colaboraciones
                        </div>
                    </div>
                </div>
            </div>

            <form
                class="contact-form reveal reveal-right"
                style="--i: 1"
                method="{{ $isStaticExport ? 'GET' : 'POST' }}"
                action="{{ $isStaticExport && $canSendStaticContact ? 'mailto:' . $contactEmail . '?subject=' . $encodedContactSubject : ($isStaticExport ? '#contacto' : route('portfolio.contact.store')) }}"
                @if ($isStaticExport) enctype="text/plain" @endif
                @if ($isStaticExport)
                    data-static-contact-form
                    data-contact-email="{{ $contactEmail }}"
                    data-contact-subject="{{ $contactSubject }}"
                @endif
            >
                @if ($isStaticExport)
                    <div class="form-message visible {{ $canSendStaticContact ? 'success' : 'error' }}" data-static-contact-message>
                        @if ($canSendStaticContact)
                            Esta versión estática abre tu cliente de correo para enviar el mensaje.
                        @else
                            Configura PORTFOLIO_CONTACT_EMAIL para habilitar el envío desde la versión estática.
                        @endif
                    </div>
                @else
                    @csrf

                    @if (session('success'))
                        <div class="form-message visible success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="form-message visible error">
                            Revisa los campos marcados antes de enviar.
                        </div>
                    @endif
                @endif

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="name">Nombre</label>

                        <input
                            class="form-input"
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Tu nombre"
                            required
                        >

                        @error('name')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Correo</label>

                        <input
                            class="form-input"
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="correo@ejemplo.cl"
                            required
                        >

                        @error('email')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="subject">
                        Tipo de proyecto
                    </label>

                    <input
                        class="form-input"
                        type="text"
                        id="subject"
                        name="subject"
                        value="{{ old('subject') }}"
                        placeholder="Sitio web, sistema, ecommerce..."
                        required
                    >

                    @error('subject')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="message">
                        Cuéntame sobre tu idea
                    </label>

                    <textarea
                        class="form-input"
                        id="message"
                        name="message"
                        placeholder="Describe brevemente tu proyecto..."
                        required
                    >{{ old('message') }}</textarea>

                    @error('message')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="button button-primary"
                    @if ($isStaticExport && ! $canSendStaticContact) disabled @endif
                >
                    Enviar mensaje
                    <span class="button-arrow">→</span>
                </button>
            </form>
        </div>
    </div>
</section>
