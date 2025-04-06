export default function Footer() {
    return (
        <footer className="flex flex-col sm:flex-row fixed bottom-0 min-w-full items-center h-auto sm:h-16 px-4 border-t md:px-6 mt-auto dark:border-gray-800 bg-white/80 dark:bg-gray-950/80 backdrop-blur-sm py-4 sm:py-0">
            <p className="text-sm text-gray-500 dark:text-gray-400">
                © 2025{" "}
                <a
                    className="hover:underline underline-offset-4 dark:text-pink-300 dark:hover:text-pink-200"
                    href="https://iqfareez.com"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Muhammad Fareez
                </a>
            </p>
            <nav className="mt-2 sm:mt-0 sm:ml-auto flex flex-wrap gap-4 sm:gap-6">
                <a
                    className="text-sm hover:underline underline-offset-4 hidden sm:block dark:text-gray-300 dark:hover:text-pink-200"
                    href="https://waktusolat.app"
                    target="_blank" 
                    rel="noopener noreferrer"
                >
                    Waktu Solat Project
                </a>
                <a
                    className="text-sm hover:underline underline-offset-4 dark:text-gray-300 dark:hover:text-pink-200"
                    href="https://docs.google.com/forms/d/e/1FAIpQLSe-zlZBW-8hO9XPDlLf-K7AUxtgupmD6bo4iouyLXFPAMnxFA/viewform?usp=sf_link"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Feedback
                </a>
                <a
                    className="text-sm hover:underline underline-offset-4 dark:text-gray-300 dark:hover:text-pink-200"
                    href="https://github.com/mptwaktusolat/mpt-server"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    GitHub
                </a>
                <a
                    className="text-sm hover:underline underline-offset-4 dark:text-gray-300 dark:hover:text-pink-200"
                    href="https://umami.iqfareez.com/share/dQGLdz7BivSE54it/api.waktusolat.app"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Analytics
                </a>
            </nav>
        </footer>
    );
}