import { Head } from '@inertiajs/react';

export default function Welcome() {
    return (
        <>
            <Head title="Nmbr 1 Val Player" />
            <div className="flex min-h-screen items-center justify-center bg-white p-6 text-center dark:bg-[#0a0a0a]">
                <h1 className="text-6xl font-extrabold uppercase text-black lg:text-9xl dark:text-white">
                    nmbr 1 val player barno created this
                </h1>
            </div>
        </>
    );
}
