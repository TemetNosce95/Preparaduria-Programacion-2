/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package numerosromanos;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author temetnosce
 */
public class NumerosRomanos {

    /**
     *
     */
    public static String[] letras = {"I","IV","V","IX","X", "XL", "L", "XC", "C", "CD", "D", "CM", "M"};
    public static int[] valores = {1, 4, 5, 9, 10, 40, 50, 90, 100, 400, 500 , 900, 1000};
    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        NumerosRomanos mainObj = new NumerosRomanos();
        BufferedReader buffer = new BufferedReader(new InputStreamReader(System.in));
        
        
        try {
            System.out.println("Ingrese el número a convertir: ");
            System.out.println(mainObj.convertir2(Integer.parseInt(buffer.readLine())));
        } catch (IOException ex) {
            Logger.getLogger(NumerosRomanos.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
    
    // A pie (no es aconsajable)
    public String convertir1(int n){ 
        String salida = "";
        
        if(n >= 1000 && n <= 3000){
            salida += repetir("M",(int)n/1000);
            n = n % 1000;
        }
        
        
        if(n >= 900 && n < 1000){
            salida += "CM";n -= 900;
        }
        
        if(n >= 500 && n < 900){
            salida += "D";
            n -= 500;
        }
        
        if(n >= 400 && n < 500){
            salida += "CD";n -= 400;
        }
        
        if(n >= 100 && n < 400){
            salida += repetir("C",(int)n/100);
            n = n  % 100;
        }
        
        if(n >= 90 && n < 100){
            salida += "XC";
            n -= 90;
        }
        
        if(n >= 50 && n < 90){
            salida += "L";
            n -= 50;
        }
        
        if(n >= 40 && n < 50){
            salida += "XL";
            n -= 40;
        }
        
        if(n >= 10){
            salida += repetir("X",(int)n/10);
            n = n%10;
        }
        
        if(n == 9){
            salida += "IX";
            n = n - 9;
        }
        
        if (n >= 5){
            salida += "V";
            n -= 5;
        }
        
        if(n == 4){
            salida += "IV";
            n -= 4;
        }
        
        if(n > 0){
            salida += repetir("I",(int)n);
            n = 0;
        }
        
        return salida;
    }
    
    //Usando aritmética básica y recorriendo un vector de valores predefinidos
    public String convertir2(int n){
        String salida = "";
        
        if(n > 0 && n < 3999){
            for (int actual = valores.length - 1; actual >= 0; actual--) {
                int x = valores[actual];
                if( n >= x){
                    salida += repetir(letras[actual], (int) n/valores[actual] );
                    n = n % valores[actual];
                }
            }
        }
        
        return salida;
    }
    public String repetir(String s, int ni){
        String st = "";
        for (int i = 0; i < ni; i++) {
            st += s;
        }
        return st;
    }
}
