<?php

use App\Ptk;
use App\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Super',
            'last_name' => 'Administrator',
            'email' => 'admin@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $this->ptk()->each(function($name){
            Ptk::create(['name' => $name]);
        });

    }

    private function ptk()
    {
        return collect([
            "Dr. SUYITNO, M.Pd",
            "LILIK RIYANTI S.Pd",
            "HARI WINARNO",
            "SUYITNO S.Pd.",
            "ARIES SUHARMINANTO S.Pd",
            "DASIH SUSILOWATI S.Pd",
            "SUROSO",
            "WATOYO S.Pd",
            "LESTARIANI M.Pd",
            "ANIK SUGIATI S. Pd",
            "SRI YULI YUANI S.Pd",
            "RINA HARIYATI",
            "SUNDAHWATI S,Pd,M.Pd",
            "DEDI AGUS EFARIYANTO S,Pd",
            "SAHLAN MARZUQI S.Pd.I",
            "CHRISTIONO S.Pd.",
            "ISMIATI S.Pd",
            "MARGA PRAYITNO S.Pd.",
            "SUEP S.Pd.",
            "JOKO PURNOMO S.Sos",
            "HINDUN SHABRIYA S.Pd",
            "AGUS SUPARGO S.Pd.",
            "GEMA RIAWAN S.Pd.",
            "SURATI S.Pd.",
            "TETY AMBARWATI S.Pd",
            "MUJAYATI",
            "RIZKA ROSLIANA S.Pd",
            "ESTU PINASTI",
            "INDAH BUDI CAHYANI S,Pd",
            "ARIEK SETYAWAN S.E, S.Pd",
            "SUTI S.Pd",
            "ASIH WIDAYATI S.Pd",
            "NAILUL HUDA S. ST",
            "RENI KARTIKA S.Pd.,MM",
            "ARIF FAJAR HARSANTO S.Pd.",
            "ERMY SULISTIANI S.Pd.",
            "KUKUH YUWONO A.Md",
            "MASKUR MUARIFIN",
            "DEVI LARASATI SANDHI S.PD.",
            "DYAH WAHYU LESTARI S.Pd.",
            "ISTIKLALIYATUZ ZUHRO S.Sn",
            "SUHARIYANTO",
            "NING FADLILLAH S.E., S.Pd.",
            "ADI PURWANTO S.Pd",
            "ARIF RUSDIANA S.Pd",
            "ARIS FIRNGADI",
            "AWIM RO'ATUN S.Pd",
            "DRESITA GUSTINARIA S.Pd",
            "EKO LUGIARTO",
            "ENDAH TRIARIANI S.Pd",
            "ENY LESTARI S.Pd",
            "ETI ARIANI S.Pd",
            "FAJAR EKO SUCIPTO",
            "HETY SETYANINGSIH S.Pd",
            "IFATUL LATIFAH S. Pd",
            "IMAM MUHADI S.T.",
            "IRA MARVINA ARTHANI S.Pd.T",
            "ISNARI S.Pd",
            "ISTARIS IRIANE BUDI S.ST",
            "LAMUJI",
            "LISA NURHAYATI S.Pd",
            "LUCKY HERMAWAN S.Pd",
            "MASTUT EFFENDI S.Pd",
            "R. SUCIYONO S.Pd",
            "SITI SOLEKAH S.Pd.",
            "SLAMET S.pd",
            "SRI LAKSMI WIDJAJANTI S.Pd",
            "SUGITO S.Pd.",
            "SUTIKNO S.Pd",
            "TOTO YUNI PURWANTO S.Sn.",
            "TRI ADJIE NUGROHO M.Pd.",
            "TRI BEKTI S.Pd",
            "TRI WAHYU WIDIYAWATI S,Pd",
            "TRI YANTI SAVITRI",
            "TUTUT WULANDARI SPd",
            "YAYUK SUCI HANDAYANI A.Md",
            "YULI KUSWARDANI",
            "YULIATI",
        ]);
    }
}
