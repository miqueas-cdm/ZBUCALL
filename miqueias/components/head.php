<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Portal do Associado' ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    primary: {
                        DEFAULT: '#0A55A1', // Brand Blue
                        50: '#F0F7FF',
                        100: '#E0EFFF',
                        200: '#B8DFFF',
                        300: '#8AC4FF',
                        400: '#52A0FF',
                        500: '#0A55A1', // Base
                        600: '#004488',
                        700: '#003366',
                        800: '#002244',
                        900: '#001122',
                    },
                    gray: {
                        50: '#F5F5F7', // Apple Light Background
                        100: '#E5E5EA',
                        200: '#D1D1D6',
                        300: '#C7C7CC',
                        400: '#AEAEB2',
                        500: '#8E8E93',
                        600: '#636366',
                        700: '#48484A',
                        800: '#1C1C1E', // Apple Dark Card (Secondary System Background)
                        900: '#000000', // Apple Dark Background (System Background)
                    }
                },
                fontFamily: {
                    sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                }
            }
        }
    }
</script>
<link rel="stylesheet" href="assets/css/styles.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
