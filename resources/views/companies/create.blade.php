
ParseError
resources/views/employees/create.blade.php:52

syntax error, unexpected token "endforeach", expecting "elseif" or "else" or "endif"
LARAVEL 13.36.0
PHP 8.5.11
UNHANDLED
CODE 0
500
GET
http://127.0.0.1:8000/employees/create?company_id=3
Exception trace

Illuminate\Filesystem\Filesystem::{closure:Illuminate\Filesystem\Filesystem::getRequire():120}()

resources/views/employees/create.blade.php:52

47                        $isSelected = (string) old('company_id', request('company_id')) === (string) $company->id;
48                        @endphp
49                        <option value="{{ $company->id }}" @selected($isSelected)>
    50                            {{ $company->name }}
    51                        </option>
52                        @endforea
