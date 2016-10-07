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
 class NuevoHilo extends Thread {
        Recurso recurso;

        public NuevoHilo(Recurso recurso) {
            this.recurso = recurso;
        }

        @Override
        public void run() {
            for (int i = 0; i < 100; i++) {
                this.cambiar();
            }
        }
        
        synchronized void cambiar(){
            //synchronized(recurso){
                if(this.recurso.isFlag()){
                        this.recurso.setFlag(false);
                        System.out.println("Hilo pasado a falso.");
                    }else{
                        this.recurso.setFlag(true);
                        System.out.println("Hilo pasado a true.");
                    }
            //}
        }
 }