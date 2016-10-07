/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package primerhilo;

/**
 *
 * @author wladimir.gonzalez
 */
public class PrimerHilo {
    EjemploHilos1 ej1,ej2;

    public PrimerHilo() {
        this.ej1 = new EjemploHilos1(1);
        this.ej2 = new EjemploHilos1(2);
    }
    
    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
       /*PrimerHilo ph = new PrimerHilo();
       
       ph.ej1.start();
       ph.ej2.start();
       
       Thread hilo1, hilo2;
       */
        
        Recurso r = new Recurso(true);
        NuevoHilo nh1 = new NuevoHilo(r);
        NuevoHilo nh2 = new NuevoHilo(r);
        
       nh1.start();
       nh2.start();
        
        
    }
    
    class EjemploHilos1 extends Thread {
        int nHilo;

        public EjemploHilos1(int nHilo) {
            this.nHilo = nHilo;
        }
        
        @Override
        public void run() {
            for (int i = 0; i < 20; i++) {
                System.out.println("Hilo # "+this.nHilo);
            }
        }
        
    }

    
    
}
