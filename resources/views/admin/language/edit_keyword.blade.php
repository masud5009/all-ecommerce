@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><span class="fas fa-home"></span></a></li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ route('admin.language') }}">{{ __('Language Managment') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ $language->name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Edit Keyword') }}</a>
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">
                                {{ __('Keywords of') . ' ' . $language->name . ' ' . __('Language') }}
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <a href="{{ route('admin.language') }}" class="btn btn-primary btn-sm float-lg-end float-left">
                                <i class="fas fa-angle-double-left"></i> {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Auto Translate Section -->
                            <div id="app" class="alert alert-info mb-4">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        @php
                                            $languages = App\Models\Admin\Language::select('code', 'name')->get();
                                        @endphp
                                        <select v-model="selectedLang" class="form-control">
                                            <option value="">Select Language</option>
                                            @foreach ($languages as $lang)
                                                <option value="{{ $lang->code }}">
                                                    {{ $lang->name }} ({{ $lang->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button @click="autoTranslateAll" :disabled="translating"
                                            class="btn btn-primary btn-sm">
                                            <i class="fas fa-magic"></i>
                                            <span v-if="!translating">Auto Translate All (Google)</span>
                                            <span v-else>Translating... (@{{ translateProgress }}%)</span>
                                        </button>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-success" v-if="translateSuccess">Successfully translated!</small>
                                        <small class="text-danger" v-if="translateError">Translation failed!</small>
                                    </div>
                                </div>
                            </div>

                            <form id="languageKeywordForm"
                                action="{{ route('admin.language.update_keyword', ['id' => $language->id]) }}"
                                method="post">
                                @csrf
                                <input type="hidden" value="{{ $userType }}" name="userType">
                                <div class="row" id="keywords-container">
                                    @foreach ($keywords as $keyword => $value)
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label for="key_{{ $loop->index }}">{{ $keyword }}</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="key_{{ $loop->index }}"
                                                        name="keys[{{ $keyword }}]" value="{{ $value }}">
                                                    <div class="input-group-append">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary btn-sm translate-single"
                                                            data-key="{{ $keyword }}">
                                                            <i class="fas fa-sync"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary" form="languageKeywordForm">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.5.22/vue.global.prod.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const {
                createApp
            } = Vue;

            createApp({
                data() {
                    return {
                        selectedLang: '{{ !empty($language) ? $language->code : 'bn' }}',
                        translating: false,
                        translateProgress: 0,
                        translateSuccess: false,
                        translateError: false
                    }
                },
                methods: {
                    async autoTranslateAll() {
                        this.translating = true;
                        this.translateSuccess = false;
                        this.translateError = false;
                        this.translateProgress = 0;

                        const inputs = document.querySelectorAll(
                            '#keywords-container input[type="text"]');
                        const total = inputs.length;
                        let done = 0;

                        for (let input of inputs) {
                            const currentText = input.value.trim();

                            if (currentText !== '') {
                                try {
                                    const translated = await this.translateText(currentText, this
                                        .selectedLang);
                                    input.value = translated;
                                } catch (error) {
                                    console.error("Translation failed for:", input.name, error);
                                }
                            }

                            done++;
                            this.translateProgress = Math.round((done / total) * 100);

                            // Avoid rate limiting
                            await new Promise(resolve => setTimeout(resolve, 300));
                        }

                        this.translating = false;
                        this.translateSuccess = true;
                        setTimeout(() => this.translateSuccess = false, 5000);
                    },

                    async translateSingle(key) {
                        const input = document.querySelector(`input[name="keys[${key}]"]`);
                        if (!input) return;

                        const currentText = input.value.trim();
                        if (currentText === '') return;

                        try {
                            const translated = await this.translateText(currentText, this.selectedLang);
                            input.value = translated;
                        } catch (error) {
                            console.error("Single translation failed:", error);
                            this.translateError = true;
                            setTimeout(() => this.translateError = false, 3000);
                        }
                    },

                    async translateText(text, targetLang) {
                        try {
                            const response = await axios.post('/admin/translate', {
                                q: text,
                                target: targetLang,
                                source: 'auto'
                            }, {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                }
                            });

                            return response.data.translated || text;
                        } catch (error) {
                            console.error("Translation API failed:", error.response?.data || error
                                .message);
                            throw new Error('Translation failed');
                        }
                    }
                },
                mounted() {
                    // Add event listeners for single translate buttons
                    document.querySelectorAll('.translate-single').forEach(button => {
                        button.addEventListener('click', () => {
                            const key = button.getAttribute('data-key');
                            this.translateSingle(key);
                        });
                    });
                }
            }).mount('#app');
        });
    </script>
@endsection
