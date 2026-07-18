<section class="section" id="contacto">
    @php
        $isStaticExport = config('portfolio.static_export', false);
        $contactEmail = trim((string) config('portfolio.contact_email', ''));
        $canSendStaticContact = $contactEmail !== '';
        $nameField = $isStaticExport ? 'nombre' : 'name';
        $emailField = $isStaticExport ? 'correo' : 'email';
        $projectTypeField = $isStaticExport ? 'tipo_proyecto' : 'subject';
        $messageField = $isStaticExport ? 'mensaje' : 'message';
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
                        Cu&eacute;ntame sobre tu idea, sistema o proyecto. Podemos
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
                        <div class="contact-detail-label">Ubicaci&oacute;n</div>
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
                id="portfolio-contact-form"
                class="contact-form reveal reveal-right"
                style="--i: 1"
                method="{{ $isStaticExport ? 'GET' : 'POST' }}"
                action="{{ $isStaticExport ? '#contacto' : route('portfolio.contact.store') }}"
                @if ($isStaticExport)
                    data-static-contact-form
                    data-contact-email="{{ $contactEmail }}"
                @endif
            >
                @if (! $isStaticExport)
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
                        <label class="form-label" for="{{ $nameField }}">Nombre</label>

                        <input
                            class="form-input"
                            type="text"
                            id="{{ $nameField }}"
                            name="{{ $nameField }}"
                            value="{{ old('name') }}"
                            placeholder="Tu nombre"
                            autocomplete="name"
                            required
                        >

                        @error('name')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="{{ $emailField }}">Correo</label>

                        <input
                            class="form-input"
                            type="email"
                            id="{{ $emailField }}"
                            name="{{ $emailField }}"
                            value="{{ old('email') }}"
                            placeholder="correo@ejemplo.cl"
                            autocomplete="email"
                            required
                        >

                        @error('email')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="{{ $projectTypeField }}">
                        Tipo de proyecto
                    </label>

                    <input
                        class="form-input"
                        type="text"
                        id="{{ $projectTypeField }}"
                        name="{{ $projectTypeField }}"
                        value="{{ old('subject') }}"
                        placeholder="Sitio web, sistema, ecommerce..."
                        autocomplete="off"
                        required
                    >

                    @error('subject')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="{{ $messageField }}">
                        Cu&eacute;ntame sobre tu idea
                    </label>

                    <textarea
                        class="form-input"
                        id="{{ $messageField }}"
                        name="{{ $messageField }}"
                        placeholder="Describe brevemente tu proyecto..."
                        autocomplete="off"
                        required
                    >{{ old('message') }}</textarea>

                    @error('message')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-actions">
                    <button
                        type="submit"
                        class="button button-primary"
                        @if ($isStaticExport && ! $canSendStaticContact) disabled @endif
                    >
                        {{ $isStaticExport ? 'Enviar por correo' : 'Enviar mensaje' }}
                        <span class="button-arrow">&rarr;</span>
                    </button>

                    @if ($isStaticExport)
                        <p class="form-submit-note {{ ! $canSendStaticContact ? 'error' : '' }}" data-static-contact-message>
                            @if ($canSendStaticContact)
                                Se abrir&aacute; tu aplicaci&oacute;n de correo con el mensaje preparado.
                            @else
                                Configura PORTFOLIO_CONTACT_EMAIL para habilitar el env&iacute;o desde la versi&oacute;n est&aacute;tica.
                            @endif
                        </p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>
