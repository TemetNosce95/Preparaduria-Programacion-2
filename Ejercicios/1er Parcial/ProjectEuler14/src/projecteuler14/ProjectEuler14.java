/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package projecteuler14;

/**
 *
 * @author jesus.reyes
 */
public class ProjectEuler14 {
    public static final long n_inicio = 1;
    public static final long n_final = 1000000;
    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        ProjectEuler14 p14 = new ProjectEuler14();
        
        //System.out.println("Tamaño cadena = " + p14.tamanoConjuntoIterativo(113383));
        long tamano_conjunto_de_iteracion = 0, tamano_conjunto_de_iteracion_mayor = -999, indice_conjunto_tamano_mayor = -9;
        for (long i = n_inicio; i <= n_final; i++) {
            tamano_conjunto_de_iteracion = p14.tamanoConjuntoIterativo(i);
            
            if(tamano_conjunto_de_iteracion > tamano_conjunto_de_iteracion_mayor){
                tamano_conjunto_de_iteracion_mayor = tamano_conjunto_de_iteracion;
                indice_conjunto_tamano_mayor = i;
            }
            
            //System.out.println("Tamaño cadena para i = "+ i +" --> " + tamano_conjunto_de_iteracion);
        }
        
        System.out.println("El número entre " + p14.n_inicio + " y " + p14.n_final +
                " Con mayor numero de iteraciones fue "+indice_conjunto_tamano_mayor+" con "+
                tamano_conjunto_de_iteracion_mayor+" iteraciones.");
    }
    
    public long tamanoConjuntoRecursivo(long n, long acumulador){
        if(n == 1) return acumulador;
        else{
            if(n % 2 == 0) return tamanoConjuntoRecursivo(n/2, acumulador+1);
            else return tamanoConjuntoRecursivo(3*n+1, acumulador+1);
        }
    }
    
    public long tamanoConjuntoIterativo(long n){
        long contador_iteraciones = 1;
        
        while(n != 1){
            if(n%2 == 0) n = n/2;
            else n = n*3 + 1;
            contador_iteraciones++;
        }
        
        return contador_iteraciones;
    }
    
}
