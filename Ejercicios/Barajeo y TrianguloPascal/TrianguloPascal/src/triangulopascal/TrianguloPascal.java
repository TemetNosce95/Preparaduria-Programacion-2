/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package triangulopascal;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author jesus.reyes
 */
public class TrianguloPascal {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        String respuesta = "";
        BufferedReader buffer = new BufferedReader(new InputStreamReader(System.in));
        System.out.println("Bienvenido al programa de generación de filas del triángulo de Pascal.");
        System.out.println("¿Desea generar filas? (s/n)");
        
        TrianguloPascal tp = new TrianguloPascal();
        
        try {
            respuesta = buffer.readLine();
        } catch (IOException ex) {
            System.err.println("Error al leer del buffer de entrada.");
        }
        
        while(respuesta.compareToIgnoreCase("s") == 0){
                System.out.print("Por favor introduzca el número de filas a generar.\nN = ");
                int numero_filas = 0;
                
            try {
                numero_filas = Integer.parseInt(buffer.readLine());
            } catch (IOException ex) {
                System.err.println("Error al leer del buffer.");
            }catch(NumberFormatException ex){
                System.err.println("Error al convertir, el valor ingresado no es un número entero.");
            }
               
            if(numero_filas > 0)
                for(int i = 0; i < numero_filas; i++){
                    System.out.println(tp.generarFila(i));
                }
            
            System.out.println("");
            System.out.println("¿Desea generar más filas? (s/n)");
        }
    }
    
    public int factorial(int n){ // Factorial recursivo, iterativo es más rápido
        if(n == 0) return 1;
        else return n*factorial(n-1);
    }
    
    public int combinatoria(int p, int q){
        return factorial(p) / (factorial(q)*factorial(p-q));
    }
    
    public String generarFila(int i){
        String salida = "";
        
        for (int k = 0; k < i+1; k++) {
            salida += combinatoria(i,k)+" ";//Genero las flas basado en los coeficientes del binomio de Newton.
            //El problema de este metodo es que cuando el factorial es muy grande se desbordan los enteros.
        }
        return salida;
    }
    
    
}
