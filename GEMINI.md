# Project: ikea-tradfri-php-api

**Description**: PHP library to control Ikea Tradfri hub.
**License**: MIT
**Author**: Benjamin Fahl

## Key Technologies
- **Language**: PHP (~8.3.0 || ~8.4.0)
- **Core Components**: 
    - Symfony (Process, Serializer, Validator, etc.)
    - Doctrine Collections
    - Roave Better Reflection
- **Testing**: Codeception
- **Quality Tools**: 
    - PHPStan
    - PHP-CS-Fixer
    - Rector
    - GrumPHP

## Recent Updates
**Data source**: `CHANGELOG.md`

- **v4.1.4 (2025-12-05)**:
    - Fixed unit tests and QA workflow issues.
- **v4.1.3 (2025-12-05)**:
    - Fixed PHPStan issues and updated dependencies.
- **v4.1.2 (2025-08-15)**:
    - Fixed PHP-CS-Fixer updates.
- **v4.1.1 (2025-07-31)**:
    - Updated dependencies and fixed code style.

## Developer Notes
- **Scripts** (`composer.json`):
    - `build-coverage`: Run tests with coverage output.
    - `php-cs-fixer-full`: Fix coding style.
    - `phpstan`: Run static analysis.
    - `run-tests`: Run Codeception tests.
- **Docker**:
    - Project uses `webproject/coap-client` for CoAP communication.
    - See `README.md` for Docker usage.

## Important Files
- `composer.json`: Application dependencies and scripts.
- `README.md`: General documentation and badges.
- `CHANGELOG.md`: Detailed version history.
- `grumphp.yml`: Git hook configuration.

## Work Standards
- **Persona**: PHP Expert, Symfony enthusiast.
- **Workflow**:
    - Run `vendor/bin/grumphp run` (with json output) after changes.
    - Use semantic commits.
    - Create feature branches from fresh `main`.
    - Follow PHP 8.3 & Symfony best practices.

## App Architecture & Understanding
- **Core Abstraction**: `GatewayApiService` serves as the high-level entry point, orchestrating interactions between the application and the Ikea Tradfri gateway.
- **Communication Layer**: 
    - Uses an `AdapterInterface` to abstract the underlying communication protocol (CoAP).
    - `Client` utilizes this adapter to send commands and receive data.
    - `Command` classes (e.g., `Get`, `Post`, `Put`) likely encapsulate specific CoAP requests.
- **Data Management**:
    - **Devices**: Modeled as strong objects (`LightBulb`, `RollerBlind`, `DeviceGroup`) residing in `Collection`s.
    - **Mappers**: `DeviceData` and `GroupData` mappers responsbile for transforming raw gateway responses into structured PHP objects.
- **Testing Strategy**:
    - **Unit Tests**: Located in `tests/Unit`, using **Codeception** and **Mockery**.
    - **Isolation**: Services and Clients are tested in isolation by mocking dependencies like `AdapterInterface`.
