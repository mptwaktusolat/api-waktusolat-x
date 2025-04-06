import { useState, useEffect } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Card } from '@/components/ui/card';
import { 
    Select, 
    SelectTrigger, 
    SelectValue, 
    SelectContent, 
    SelectItem 
} from '@/components/ui/select';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Data Health',
        href: '/data-health',
    },
];

interface Zone {
    jakim_code: string;
}

interface MonthData {
    month: string;
    monthNumber: number;
    isAvailable: boolean;
}

interface MonthCardProps {
    month: string;
    monthNumber: number;
    isAvailable: boolean;
}

interface DataHealthProps {
    zones: Zone[];
    selectedZone: string;
    selectedYear: number;
    monthsAvailability: MonthData[];
}

export default function DataHealth({ 
    zones = [], 
    selectedZone, 
    selectedYear,
    monthsAvailability = []
}: DataHealthProps) {
    const [year, setYear] = useState<number>(selectedYear);
    const [zone, setZone] = useState<string>(selectedZone);
    
    // Generate years for dropdown (current year and 2 years before/after)
    const currentYear = new Date().getFullYear();
    const years = Array.from({ length: currentYear - 2023 + 2 }, (_, i) => 2023 + i);
    
    // Update the URL when year or zone changes
    useEffect(() => {
        router.visit(`/data-health?zone=${zone}&year=${year}`, {
            preserveState: true,
            replace: true,
            only: ['monthsAvailability', 'selectedZone', 'selectedYear'],
        });
    }, [year, zone]);
    
    return (
        <>
            <Head title="Data Health" />
            <div className="flex flex-col gap-6 p-8 pt-12 max-w-7xl mx-auto">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <h1 className="text-2xl font-bold">
                        <a 
                            href="/" 
                            className="text-blue-600 dark:text-blue-400 hover:underline"
                        >
                            Home{" "}
                        </a> 
                        / Data Health
                    </h1>
                    <div className="flex flex-col gap-3 sm:flex-row">
                        <div className="flex flex-col gap-1.5">
                            <label htmlFor="year-select" className="text-sm font-medium">
                                Year
                            </label>
                            <Select value={year.toString()} onValueChange={(value) => setYear(Number(value))}>
                                <SelectTrigger id="year-select" className="w-[180px]">
                                    <SelectValue placeholder="Select year" />
                                </SelectTrigger>
                                <SelectContent>
                                    {years.map((y) => (
                                        <SelectItem key={y} value={y.toString()}>
                                            {y}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        
                        <div className="flex flex-col gap-1.5">
                            <label htmlFor="zone-select" className="text-sm font-medium">
                                Zone
                            </label>
                            <Select value={zone} onValueChange={(value) => setZone(value)}>
                                <SelectTrigger id="zone-select" className="w-[220px]">
                                    <SelectValue placeholder="Select zone" />
                                </SelectTrigger>
                                <SelectContent>
                                    {zones.map((z) => (
                                        <SelectItem key={z.jakim_code} value={z.jakim_code}>
                                            {z.jakim_code}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                </div>
                
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
                    {monthsAvailability.map((monthData) => (
                        <MonthCard
                            key={monthData.month}
                            month={monthData.month}
                            monthNumber={monthData.monthNumber}
                            isAvailable={monthData.isAvailable}
                        />
                    ))}
                </div>
                
                <div className="rounded-lg border border-sidebar-border/70 dark:border-sidebar-border p-4 text-sm text-gray-600 dark:text-gray-400 space-y-2">
                    <p>All data is checked:</p>
                    <ul className="list-disc list-inside ml-4 space-y-1">
                        <li key="check-zone">based on {zone} zone</li>
                        <li key="check-api">against v2 solat API. Hence, data prior May 2023 is expected to be not available</li>
                    </ul>
                    <p>
                        Prayer time database is updated periodically from e-solat JAKIM portal. 
                        See fetcher implementation on <a 
                            href="https://github.com/mptwaktusolat/waktusolat-fetcher" 
                            className="text-blue-600 dark:text-blue-400 hover:underline"
                        >
                            GitHub
                        </a>.
                    </p>
                </div>
            </div>
        </>
    );
}

function MonthCard({ month, monthNumber, isAvailable }: MonthCardProps) {
    const getStatusColor = () => {
        return isAvailable 
            ? 'bg-green-100 dark:bg-green-800/50' 
            : 'bg-red-100 dark:bg-red-800/50';
    };
    
    return (
        <Card className={`p-4 ${getStatusColor()} transition-colors`}>
            <div className="flex flex-col">
                <h3 className="font-semibold">{month}</h3>
                <p className="text-sm font-medium">
                    {isAvailable 
                        ? 'Data Available' 
                        : 'No Data'}
                </p>
            </div>
        </Card>
    );
}